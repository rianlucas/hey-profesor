<?php

use App\Models\{Question, User};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

it('should paginate the result', function () {
    $user      = User::factory()->create();
    $questions = Question::factory()->count(20)->create();

    actingAs($user);

    $response = get(route('dashboard'))
        ->assertViewHas('questions', fn ($value) => $value instanceof LengthAwarePaginator);
});

it('should order by like and unlike, most liked question should be at the top, monst unlike questions should nbe in the bottom', function () {
    $user       = User::factory()->create();
    $secondUser = User::factory()->create();
    Question::factory()->count(5)->create();

    $mostLikedQuestion   = Question::find(3);
    $mostUnlikedQuestion = Question::find(1);

    $user->like($mostLikedQuestion);
    $secondUser->unlike($mostUnlikedQuestion);

    actingAs($user);
    get(route('dashboard'))
        ->assertViewHas('questions', function ($questions) {
            expect($questions)
                ->first()->id
                ->toBe(3);

            expect($questions)
                ->last()->id
                ->toBe(1);

            return true;
        });

});
