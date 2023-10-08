<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, get, put};

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

it('should not be able to update a question bigger than 255 characters', function () {
    $user     = User::factory()->create();
    $question = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => true]);
    actingAs($user);

    $request = put(route('question.update', $question), [
        'question' => str_repeat('*', 260) . '?',
    ]);

    $request->assertRedirect();
    assertDatabaseCount('questions', 1);
    assertDatabaseHas('questions', ['question' => str_repeat('*', 260) . '?']);
});

it('should check if end with question mark ?', function () {
    $user     = User::factory()->create();
    $question = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => true]);
    actingAs($user);

    actingAs($user);

    $request = put(route('question.update', $question), [
        'question' => str_repeat('*', 10),
    ]);

    $request->assertSessionHasErrors([
        'question' => 'Are you sure that is a question ? It is missing the question mark',
    ]);
    assertDatabaseHas('questions', ['question' => $question->question]);
    assertDatabaseCount('questions', 1);
});

it('should have at least 10 character', function () {
    $user     = User::factory()->create();
    $question = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => true]);

    actingAs($user);

    $request = put(route('question.update', $question), [
        'question' => str_repeat('*', 8) . '?',
    ]);

    $request->assertSessionHasErrors(['question' => __('validation.min.string', ['min' => 10 , 'attribute' => 'question'])]);
    assertDatabaseHas('questions', ['question' => $question->question]);
    assertDatabaseCount('questions', 1);
});
