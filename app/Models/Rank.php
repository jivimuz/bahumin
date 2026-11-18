<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rank extends Model
{
    protected $fillable = [
        'level',
        'name',
        'description',
        'min_xp',
        'badge_icon',
        'color',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'min_xp' => 'integer',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
