<?php

use App\Models\User;

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, post};

it('should be able to create a question bigger than 255 characters', function () {
    $user = User::factory()->create();
    actingAs($user);

    $request = post(route('question.store'), [
        'question' => str_repeat('*', 260) . '?',
    ]);

    $request->assertRedirect();
    assertDatabaseCount('questions', 1);
    assertDatabaseHas('questions', ['question' => str_repeat('*', 260) . '?']);
});

it('should create as a draft all the time', function () {
    $user = User::factory()->create();
    actingAs($user);

    $request = post(route('question.store'), [
        'question' => str_repeat('*', 15) . '?',
    ]);

    assertDatabaseHas('questions', [
        'question' => str_repeat('*', 15) . '?',
        'draft'    => true,
    ]);
});

it('should check if end with question mark ?', function () {
    $user = User::factory()->create();
    actingAs($user);

    $request = post(route('question.store'), [
        'question' => str_repeat('*', 10),
    ]);

    $request->assertSessionHasErrors([
        'question' => 'Are you sure that is a question ? It is missing the question mark',
    ]);
    assertDatabaseCount('questions', 0);
});

it('should have at least 10 character', function () {
    $user = User::factory()->create();
    actingAs($user);

    $request = post(route('question.store'), [
        'question' => str_repeat('*', 8) . '?',
    ]);

    $request->assertSessionHasErrors(['question' => __('validation.min.string', ['min' => 10 , 'attribute' => 'question'])]);
    assertDatabaseCount('questions', 0);
});

test('only authenticated users can create a new question', function () {
    post(route('question.store', [
        'question' => str_repeat('*', 10) . '?',
    ]))
        ->assertRedirect(route('login'));

});
