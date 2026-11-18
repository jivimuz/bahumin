<?php

namespace Database\Seeders;

use App\Models\Rank;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $rank = Rank::where('level', 12)->first() ?? Rank::first();

        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin Bahumin',
                'username' => 'superadmin',
                'password' => Hash::make('12341234'),
                'is_admin' => true,
                'rank_id' => $rank?->id,
                'total_xp' => 5000,
                'bio' => 'Super administrator utama Bahumin (Bapak Humor Indonesia).',
            ]
        );
    }
}
