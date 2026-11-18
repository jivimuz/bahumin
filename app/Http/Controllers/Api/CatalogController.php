<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Joke;
use App\Models\User;
use App\Models\UserXpTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    public function jokes(Request $request): JsonResponse
    {
        $query = Joke::with(['user.rank', 'category'])
            ->where('status', 'published');

        if ($categorySlug = $request->query('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($request->query('sort') === 'popular') {
            $query->withCount('reactions')->orderBy('reactions_count', 'desc');
        } else {
            $query->latest();
        }

        $jokes = $query->paginate(9);

        return response()->json([
            'data' => $jokes->getCollection()->map(fn (Joke $joke) => [
                'id' => $joke->id,
                'setup' => $joke->setup,
                'punchline' => $joke->punchline,
                'user' => [
                    'name' => $joke->user->name,
                    'username' => $joke->user->username,
                ],
                'category' => $joke->category ? ['name' => $joke->category->name] : null,
            ]),
            'current_page' => $jokes->currentPage(),
            'last_page' => $jokes->lastPage(),
            'next_page_url' => $jokes->nextPageUrl(),
            'prev_page_url' => $jokes->previousPageUrl(),
        ]);
    }

    public function users(Request $request): JsonResponse
    {
        $weekly = $request->query('tab') === 'weekly';
        $currentUser = Auth::user();
        $currentUserRank = null;

        if ($weekly) {
            // 7 hari terakhir: urutan berdasarkan jumlah XP yang diperoleh minggu ini
            // Rank yang ditampilkan tetap rank asli user (dari total_xp sepanjang waktu)
            $scores = UserXpTransaction::select('user_id', DB::raw('SUM(amount) as weekly_xp'))
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('user_id')
                ->orderByDesc('weekly_xp')
                ->paginate(15);
            $users = User::with('rank')->whereIn('id', $scores->getCollection()->pluck('user_id'))->get()->keyBy('id');
            $rows = $scores->getCollection()->map(fn ($score) => [
                'user' => $users->get($score->user_id),
                'weekly_xp' => (int) $score->weekly_xp,
            ])->filter(fn ($row) => $row['user'] !== null)->values();

            if ($currentUser) {
                $weeklyXp = (int) UserXpTransaction::where('user_id', $currentUser->id)->where('created_at', '>=', now()->subDays(7))->sum('amount');
                $rankPos = UserXpTransaction::select('user_id', DB::raw('SUM(amount) as total'))
                    ->where('created_at', '>=', now()->subDays(7))->groupBy('user_id')->having('total', '>', $weeklyXp)->get()->count() + 1;
                $currentUserRank = ['rank' => $rankPos, 'xp' => $weeklyXp];
            }
        } else {
            $scores = User::with('rank')->orderByDesc('total_xp')->paginate(15);
            $rows = $scores->getCollection()->map(fn (User $user) => ['user' => $user, 'xp' => $user->total_xp])->values();
            if ($currentUser) {
                $currentUserRank = [
                    'rank' => User::where('total_xp', '>', $currentUser->total_xp)->count() + 1,
                    'xp' => $currentUser->total_xp,
                ];
            }
        }

        return response()->json([
            'data' => $rows->map(fn ($row, $index) => [
                'rank' => ($scores->currentPage() - 1) * $scores->perPage() + $index + 1,
                'name' => $row['user']->name,
                'username' => $row['user']->username,
                // Selalu tampilkan total XP (sepanjang waktu) dan rank asli user
                'xp' => $row['user']->total_xp,
                'weekly_xp' => $row['weekly_xp'] ?? null,
                'level' => $row['user']->rank?->level ?? 1,
                'rank_name' => $row['user']->rank?->name ?? 'Anak Ecek-Ecek',
                'url' => route('profile.user', $row['user']->username),
            ]),
            'current_user' => $currentUserRank,
            'current_page' => $scores->currentPage(),
            'last_page' => $scores->lastPage(),
            'next_page_url' => $scores->nextPageUrl(),
            'prev_page_url' => $scores->previousPageUrl(),
        ]);
    }
}
