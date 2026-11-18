<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JokeReaction extends Model
{
    protected $fillable = [
        'joke_id',
        'user_id',
        'reaction_type',
    ];

    public function joke(): BelongsTo
    {
        return $this->belongsTo(Joke::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
