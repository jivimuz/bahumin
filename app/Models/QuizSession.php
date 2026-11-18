<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizSession extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'status',
        'total_questions',
        'correct_answers',
        'score',
        'xp_earned',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'total_questions' => 'integer',
            'correct_answers' => 'integer',
            'score' => 'integer',
            'xp_earned' => 'integer',
            'completed_at' => 'datetime',
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

    public function sessionQuestions(): HasMany
    {
        return $this->hasMany(QuizSessionQuestion::class)->orderBy('order_index');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function currentQuestion(): ?QuizSessionQuestion
    {
        return $this->sessionQuestions()
            ->whereNull('user_answer')
            ->orderBy('order_index')
            ->first();
    }
}
