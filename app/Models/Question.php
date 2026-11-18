<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'category_id',
        'question_text',
        'type',
        'difficulty',
        'punchline',
        'accepted_answers',
        'status',
    ];

    protected $casts = [
        'accepted_answers' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class)->orderBy('order');
    }

    public function correctOption(): ?QuestionOption
    {
        return $this->options()->where('is_correct', true)->first();
    }
}
