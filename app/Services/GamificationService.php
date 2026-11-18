<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Rank;
use App\Models\User;
use App\Models\UserXpTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GamificationService
{
    /**
     * Award XP to user with ledger tracking and idempotency protection.
     */
    public function awardXp(
        User $user,
        int $amount,
        string $reason,
        ?string $referenceType = null,
        ?string $referenceId = null,
        ?string $idempotencyKey = null
    ): ?UserXpTransaction {
        if ($amount <= 0) {
            return null;
        }

        $idempotencyKey = $idempotencyKey ?: Str::uuid()->toString();

        return DB::transaction(function () use ($user, $amount, $reason, $referenceType, $referenceId, $idempotencyKey) {
            // Check idempotency
            $existing = UserXpTransaction::where('idempotency_key', $idempotencyKey)->first();
            if ($existing) {
                return $existing;
            }

            // Create ledger entry
            $transaction = UserXpTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'reason' => $reason,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'idempotency_key' => $idempotencyKey,
            ]);

            // Update user total XP
            $user->increment('total_xp', $amount);
            $user->refresh();

            // Check and update rank
            $this->updateUserRank($user);

            // Check milestone achievements
            $this->checkXpMilestones($user);

            return $transaction;
        });
    }

    /**
     * Re-calculate and update user rank according to total XP.
     */
    public function updateUserRank(User $user): ?Rank
    {
        $currentXp = $user->total_xp;

        $appropriateRank = Rank::where('min_xp', '<=', $currentXp)
            ->orderBy('min_xp', 'desc')
            ->first();

        if ($appropriateRank && $user->rank_id !== $appropriateRank->id) {
            $user->rank_id = $appropriateRank->id;
            $user->save();
        }

        if ($appropriateRank) {
            DB::table('user_ranks')->updateOrInsert(
                ['user_id' => $user->id, 'rank_id' => $appropriateRank->id],
                ['achieved_xp' => $currentXp, 'assigned_at' => now(), 'updated_at' => now()]
            );
        }

        return $appropriateRank;
    }

    /**
     * Check and award achievements based on event type.
     *
     * @return array<Achievement>
     */
    public function checkAchievements(User $user, string $requirementType, int $currentValue): array
    {
        $unlocked = [];

        $candidates = Achievement::where('requirement_type', $requirementType)
            ->where('requirement_value', '<=', $currentValue)
            ->whereNotIn('id', function ($query) use ($user) {
                $query->select('achievement_id')
                    ->from('user_achievements')
                    ->where('user_id', $user->id);
            })
            ->get();

        foreach ($candidates as $achievement) {
            $user->achievements()->attach($achievement->id, [
                'unlocked_at' => now(),
            ]);

            // Award bonus XP if defined
            if ($achievement->xp_bonus > 0) {
                $this->awardXp(
                    $user,
                    $achievement->xp_bonus,
                    "Badge Unlock: {$achievement->name}",
                    'achievement',
                    (string) $achievement->id,
                    "achievement_unlock_{$user->id}_{$achievement->id}"
                );
            }

            $unlocked[] = $achievement;
        }

        return $unlocked;
    }

    /**
     * Check XP milestone achievements.
     */
    protected function checkXpMilestones(User $user): void
    {
        $this->checkAchievements($user, 'xp_milestone', $user->total_xp);
    }
}
