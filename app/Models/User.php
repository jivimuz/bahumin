<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'total_xp',
        'rank_id',
        'streak_days',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'total_xp' => 'integer',
            'streak_days' => 'integer',
            'is_admin' => 'boolean',
        ];
    }

    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class);
    }

    public function userRanks(): BelongsToMany
    {
        return $this->belongsToMany(Rank::class, 'user_ranks')
            ->withPivot(['achieved_xp', 'assigned_at'])
            ->withTimestamps();
    }

    public function xpTransactions(): HasMany
    {
        return $this->hasMany(UserXpTransaction::class);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('unlocked_at')
            ->withTimestamps();
    }

    public function quizSessions(): HasMany
    {
        return $this->hasMany(QuizSession::class);
    }

    public function jokes(): HasMany
    {
        return $this->hasMany(Joke::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(JokeBookmark::class);
    }

    public function getNextRankAttribute(): ?Rank
    {
        return Rank::where('min_xp', '>', $this->total_xp)
            ->orderBy('min_xp', 'asc')
            ->first();
    }

    public function getRankProgressAttribute(): int
    {
        $currentRank = $this->rank;
        $nextRank = $this->next_rank;

        if (! $nextRank) {
            return 100;
        }

        $minXp = $currentRank ? $currentRank->min_xp : 0;
        $needed = $nextRank->min_xp - $minXp;
        $current = $this->total_xp - $minXp;

        if ($needed <= 0) {
            return 100;
        }

        return (int) min(100, max(0, round(($current / $needed) * 100)));
    }

    public function getAccuracyAttribute(): int
    {
        $total = QuizSessionQuestion::whereHas('quizSession', function ($q) {
            $q->where('user_id', $this->id)->where('status', 'completed');
        })->whereNotNull('user_answer')->count();

        if ($total === 0) {
            return 0;
        }

        $correct = QuizSessionQuestion::whereHas('quizSession', function ($q) {
            $q->where('user_id', $this->id)->where('status', 'completed');
        })->where('is_correct', true)->count();

        return (int) round(($correct / $total) * 100);
    }
}
