<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Joke;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DummyController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'count' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $count = $validated['count'] ?? 1;
        $maxDummyRankId = Rank::where('level', '<', 5)->max('id') ?? Rank::where('level', 1)->value('id');

        $firstNames = ['Eko', 'Slamet', 'Joko', 'Budi', 'Agus', 'Wahyudi', 'Siti', 'Sri', 'Dewi', 'Bambang', 'Suwarto', 'Dadang', 'Ujang', 'Tarno', 'Wati'];
        $lastNames = ['Santoso', 'Prasetyo', 'Hartono', 'Kusuma', 'Wibowo', 'Saputra', 'Hidayat', 'Nugroho', 'Siregar', 'Kurniawan', 'Gunawan', 'Supriyadi', 'Pangestu'];

        DB::transaction(function () use ($validated, $count, $maxDummyRankId, $firstNames, $lastNames): void {
            for ($i = 0; $i < $count; $i++) {
                $token = strtolower(Str::random(8));
                $fullName = $firstNames[array_rand($firstNames)].' '.$lastNames[array_rand($lastNames)];
                $username = strtolower(str_replace(' ', '_', $fullName)).'_'.substr($token, 0, 4);
                $xp = rand(0, 400);
                $rankId = Rank::where('min_xp', '<=', $xp)->where('level', '<', 5)->orderByDesc('min_xp')->value('id') ?? $maxDummyRankId;

                $user = User::create([
                    'name' => $fullName,
                    'username' => $username,
                    'email' => 'warga.'.$username.'@bahumin.test',
                    'password' => Str::random(40),
                    'bio' => 'Warga warkop penikmat kopi tubruk dan tebakan receh.',
                    'rank_id' => $rankId,
                    'total_xp' => $xp,
                    'streak_days' => rand(1, 5),
                    'is_admin' => false,
                ]);

                Joke::create([
                    'user_id' => $user->id,
                    'category_id' => $validated['category_id'],
                    'title' => 'Tebakan warkop',
                    'setup' => fake()->randomElement([
                        'Kenapa kopi di warkop tidak pernah telat?',
                        'Apa yang dilakukan bapak-bapak saat Wi-Fi mati?',
                        'Kenapa gelas kopi selalu siap di meja?',
                        'Tebakan apa yang paling disukai langganan warkop?',
                        'Kenapa tukang sate kalau mangkal selalu bawa kipas?',
                        'Minuman apa yang kalau diminum bikin semangat kerja lembur?',
                    ]).' #'.substr($token, 0, 4),
                    'punchline' => fake()->randomElement([
                        'Karena selalu ada yang sudah memesannya lebih dulu.',
                        'Mengobrol, lalu menanyakan Wi-Fi lagi lima menit kemudian.',
                        'Karena tugasnya memang menunggu kopi dituang.',
                        'Tebakan yang jawabannya membuat meja ikut tertawa.',
                        'Biar sate nya gak kepanasan pas lagi nongkrong.',
                        'Es teh manis dicampur semangat mantan yang udah move on.',
                    ]),
                    'status' => 'published',
                ]);
            }
        });

        return redirect()->route('admin.jokes.index')->with('success', $count.' user dummy dan jokes dummy berhasil dibuat.');
    }
}
