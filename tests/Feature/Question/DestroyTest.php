<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, assertDatabaseMissing, delete, put};

it('should be able to destroy a question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => true]);

    actingAs($user);

    delete(route('question.destroy', $question))
        ->assertRedirect();

    assertDatabaseMissing('questions', [
        'id' => $question->id,
    ]);
});

it('should make sure that the only person that has create the question can delete the question', function () {
    $rightUser = User::factory()->create();
    $question  = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);
    $wrongUser = User::factory()->create();

    actingAs($wrongUser);

    delete(route('question.destroy', $question))
        ->assertForbidden();

    actingAs($rightUser);

    delete(route('question.destroy', $question))
        ->assertRedirect();
});
