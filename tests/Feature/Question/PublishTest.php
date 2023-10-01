<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, put};

it('should be able to publish a question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()
        ->for($user, 'createdBy')
        ->create(['draft' => true]);

    actingAs($user);

    put(route('question.publish', $question))
        ->assertRedirect();

    $question->refresh();

    expect($question)
        ->draft->toBeFalse();
});

it('should make sure that the only person that has create the question can publish the question', function () {
    $rightUser = User::factory()->create();
    $question  = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);
    $wrongUser = User::factory()->create();

    actingAs($wrongUser);

    put(route('question.publish', $question))
        ->assertForbidden();

    actingAs($rightUser);

    put(route('question.publish', $question))
        ->assertRedirect();
});
