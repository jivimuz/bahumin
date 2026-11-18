<?php

namespace Tests\Feature;

use App\Models\Joke;
use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserInteractionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_user_can_register_and_login_with_username(): void
    {
        $this->post('/register', [
            'name' => 'Warga Baru',
            'username' => 'warga_baru',
            'email' => 'baru@example.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect('/');
        $this->assertAuthenticated();

        $this->post('/logout')->assertRedirect('/');
        $this->post('/login', ['login' => 'warga_baru', 'password' => 'rahasia123'])->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_user_can_react_comment_and_bookmark_a_joke(): void
    {
        $user = User::where('is_admin', true)->firstOrFail();
        $joke = Joke::firstOrFail();

        $this->actingAs($user)->post("/jokes/{$joke->id}/react", ['reaction_type' => 'ngakak'])->assertRedirect();
        $this->assertDatabaseHas('joke_reactions', ['joke_id' => $joke->id, 'user_id' => $user->id, 'reaction_type' => 'ngakak']);

        $this->actingAs($user)->post("/jokes/{$joke->id}/comment", ['comment_text' => 'Kopi saya ikut tumpah.'])->assertRedirect();
        $this->assertDatabaseHas('joke_comments', ['joke_id' => $joke->id, 'user_id' => $user->id, 'comment_text' => 'Kopi saya ikut tumpah.']);

        $this->actingAs($user)->post("/jokes/{$joke->id}/bookmark")->assertRedirect();
        $this->assertDatabaseHas('joke_bookmarks', ['joke_id' => $joke->id, 'user_id' => $user->id]);
    }

    public function test_user_can_play_quiz_and_view_result(): void
    {
        $user = User::where('is_admin', true)->firstOrFail();

        $this->actingAs($user)->post('/quiz/start', ['count' => 1])->assertRedirect();
        $session = QuizSession::where('user_id', $user->id)->latest('id')->firstOrFail();
        $this->actingAs($user)->get("/quiz/{$session->id}")->assertOk();

        $question = $session->sessionQuestions()->with('question.options')->firstOrFail();
        $answer = $question->question->type === 'multiple_choice'
            ? (string) $question->question->options->where('is_correct', true)->firstOrFail()->id
            : $question->question->accepted_answers[0];

        $this->actingAs($user)->post("/quiz/{$session->id}/answer", [
            'question_id' => $question->question_id,
            'answer' => $answer,
        ])->assertRedirect("/quiz/{$session->id}/result");

        $this->actingAs($user)->get("/quiz/{$session->id}/result")->assertOk()->assertSee('Detail Jawaban');
        $this->assertSame('completed', $session->fresh()->status);
    }
}
