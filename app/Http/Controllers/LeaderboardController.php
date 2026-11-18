<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserXpTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'all_time');
        $currentUser = Auth::user();

        if ($tab === 'weekly') {
            // Weekly leaderboard: sum XP from transactions in past 7 days
            $weeklyScores = UserXpTransaction::select('user_id', DB::raw('SUM(amount) as weekly_xp'))
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('user_id')
                ->orderByDesc('weekly_xp')
                ->limit(20)
                ->get();

            $userIds = $weeklyScores->pluck('user_id');
            $usersById = User::with('rank')->whereIn('id', $userIds)->get()->keyBy('id');

            $leaders = $weeklyScores->map(function ($row, $index) use ($usersById) {
                $user = $usersById->get($row->user_id);

                return [
                    'rank' => $index + 1,
                    'user' => $user,
                    'xp' => (int) $row->weekly_xp,
                ];
            })->filter(fn ($item) => $item['user'] !== null);

            $currentUserRank = null;
            if ($currentUser) {
                $myWeekly = UserXpTransaction::where('user_id', $currentUser->id)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->sum('amount');

                $myRankPos = UserXpTransaction::select('user_id', DB::raw('SUM(amount) as total'))
                    ->where('created_at', '>=', now()->subDays(7))
                    ->groupBy('user_id')
                    ->having('total', '>', $myWeekly)
                    ->get()
                    ->count() + 1;

                $currentUserRank = [
                    'rank' => $myRankPos,
                    'xp' => (int) $myWeekly,
                ];
            }
        } else {
            // All-time leaderboard: by total_xp
            $topUsers = User::with('rank')
                ->orderBy('total_xp', 'desc')
                ->limit(20)
                ->get();

            $leaders = $topUsers->map(function ($u, $index) {
                return [
                    'rank' => $index + 1,
                    'user' => $u,
                    'xp' => $u->total_xp,
                ];
            });

            $currentUserRank = null;
            if ($currentUser) {
                $myRankPos = User::where('total_xp', '>', $currentUser->total_xp)->count() + 1;
                $currentUserRank = [
                    'rank' => $myRankPos,
                    'xp' => $currentUser->total_xp,
                ];
            }
        }

        return view('leaderboard.index', compact('leaders', 'tab', 'currentUser', 'currentUserRank'));
    }
}
