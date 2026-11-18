<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Joke extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'setup',
        'punchline',
        'status',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'views_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(JokeReaction::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(JokeComment::class)->latest();
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(JokeBookmark::class);
    }

    public function reactionCounts(): array
    {
        return [
            'ngakak' => $this->reactions()->where('reaction_type', 'ngakak')->count(),
            'garing' => $this->reactions()->where('reaction_type', 'garing')->count(),
            'bapak' => $this->reactions()->where('reaction_type', 'bapak')->count(),
            'jenius' => $this->reactions()->where('reaction_type', 'jenius')->count(),
        ];
    }

    public function userReaction(?int $userId): ?string
    {
        if (! $userId) {
            return null;
        }

        return $this->reactions()->where('user_id', $userId)->value('reaction_type');
    }

    public function isBookmarkedBy(?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

        return $this->bookmarks()->where('user_id', $userId)->exists();
    }
}
