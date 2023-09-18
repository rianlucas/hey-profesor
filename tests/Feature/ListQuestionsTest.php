<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, get};

it('Should list all questions', function () {
    $user      = User::factory()->create();
    $questions = Question::factory()->count(5)->create();

    actingAs($user);
    $response = get(route('dashboard'));

    /** @var Question $q */
    foreach ($questions as $q) {
        $response->assertSee($q->question);
    }
    $response->assertStatus(200);
});
