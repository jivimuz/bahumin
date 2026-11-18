<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizSessionQuestion extends Model
{
    protected $fillable = [
        'quiz_session_id',
        'question_id',
        'order_index',
        'user_answer',
        'is_correct',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'order_index' => 'integer',
            'is_correct' => 'boolean',
            'answered_at' => 'datetime',
        ];
    }

    public function quizSession(): BelongsTo
    {
        return $this->belongsTo(QuizSession::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
