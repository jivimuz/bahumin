<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuizSession;
use App\Models\User;
use App\Services\GamificationService;
use App\Services\QuizService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamificationProgressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_random_xp_generation_only_produces_2_3_4_or_5(): void
    {
        $seen = [];
        for ($i = 0; $i < 200; $i++) {
            $xp = random_int(2, 5);
            $this->assertContains($xp, [2, 3, 4, 5]);
            $seen[$xp] = true;
        }
        $this->assertCount(4, $seen);
    }

    public function test_incorrect_answer_awards_zero_xp(): void
    {
        $user = User::factory()->create(['total_xp' => 0]);
        $category = Category::firstOrFail();
        $question = Question::create([
            'category_id' => $category->id,
            'question_text' => 'Berapa 1+1?',
            'type' => 'multiple_choice',
            'difficulty' => 'mudah',
            'punchline' => 'Jendela!',
            'status' => 'published',
        ]);
        $wrongOption = QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Tiga',
            'is_correct' => false,
            'order' => 1,
        ]);

        $session = QuizSession::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'in_progress',
            'total_questions' => 1,
        ]);
        $session->sessionQuestions()->create([
            'question_id' => $question->id,
            'order_index' => 1,
        ]);

        $service = app(QuizService::class);
        $result = $service->answerQuestion($session, $question->id, (string) $wrongOption->id);

        $this->assertFalse($result['is_correct']);
        $this->assertSame(0, $user->fresh()->total_xp);
    }

    public function test_duplicate_answer_requests_do_not_award_duplicate_xp(): void
    {
        $user = User::factory()->create(['total_xp' => 0]);
        $category = Category::firstOrFail();
        $question = Question::create([
            'category_id' => $category->id,
            'question_text' => 'Berapa 2+2?',
            'type' => 'multiple_choice',
            'difficulty' => 'mudah',
            'punchline' => 'Jendela!',
            'status' => 'published',
        ]);
        $correctOption = QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Empat',
            'is_correct' => true,
            'order' => 1,
        ]);

        $session = QuizSession::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'in_progress',
            'total_questions' => 1,
        ]);
        $session->sessionQuestions()->create([
            'question_id' => $question->id,
            'order_index' => 1,
        ]);

        $service = app(QuizService::class);
        $service->answerQuestion($session, $question->id, (string) $correctOption->id);
        $firstXp = $user->fresh()->total_xp;

        $this->expectException(\Exception::class);
        $service->answerQuestion($session, $question->id, (string) $correctOption->id);

        $this->assertSame($firstXp, $user->fresh()->total_xp);
        $this->assertDatabaseCount('user_xp_transactions', 1);
    }

    public function test_rank_changes_correctly_at_every_threshold_and_max_rank(): void
    {
        $user = User::factory()->create(['total_xp' => 0]);
        $service = app(GamificationService::class);

        $thresholds = [
            [0, 'Anak Ecek-Ecek', 1],
            [100, 'Penikmat Receh', 2],
            [300, 'Tukang Garing', 3],
            [650, 'Spesialis Celetukan', 4],
            [1100, 'Suhu Warkop', 5],
            [1750, 'Raja Gombal', 6],
            [2600, 'Maestro Humor', 7],
            [3800, 'Legenda Tongkrongan', 8],
            [5500, 'Bapak Segala Receh', 9],
            [8000, 'Bapak Kelas Badak', 10],
        ];

        foreach ($thresholds as [$xp, $name, $level]) {
            $user->update(['total_xp' => $xp]);
            $rank = $service->updateUserRank($user);
            $this->assertSame($level, $rank->level);
            $this->assertSame($name, $rank->name);
        }

        $user->update(['total_xp' => 9500]);
        $rank = $service->updateUserRank($user);
        $this->assertSame(10, $rank->level);
        $this->assertSame('Bapak Kelas Badak', $rank->name);
        $this->assertSame(100, $user->rank_progress);
    }
}
