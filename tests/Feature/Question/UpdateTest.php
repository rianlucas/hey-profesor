<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, get, put};

it('should be able to update a question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => true]);

    actingAs($user);

    put(route('question.update', $question), [
        'question' => 'Updated Question?',
    ])->assertRedirect();

    $question->refresh();

    expect($question->question)->toBe('Updated Question?');
});

it('should make sure that only questions with status DRAFT can be updated', function () {
    $user            = User::factory()->create();
    $questionNoDraft = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => false]);

    $draftQuestion = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => true]);

    actingAs($user);

    put(route('question.update', $questionNoDraft))->assertForbidden();
    put(route('question.update', $draftQuestion), ['question' => 'Updated Question ?'])->assertRedirect();
});

it('should make sure that only the person who has created the question can update the question', function () {
    $user      = User::factory()->create();
    $wrongUser = User::factory()->create();

    $question = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => true]);

    actingAs($wrongUser);

    put(route('question.update', $question), [
        'question' => 'Updated Question?',
    ])->assertForbidden();

    actingAs($user);

    put(route('question.update', $question), [
        'question' => 'Updated Question?',
    ])->assertRedirect();

    $question->refresh();

    expect($question->question)->toBe('Updated Question?');
});
