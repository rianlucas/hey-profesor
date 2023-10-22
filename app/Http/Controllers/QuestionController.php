<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Rules\{EndWithQuestionMarkRule, SameQuestionRule};
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse, Request, Response};

class QuestionController extends Controller
{
    public function index(): View
    {
        return view('question.index', [
            'questions'        => user()->questions,
            'archivedQuestion' => user()->questions()->onlyTrashed()->get(),
        ]);
    }
    public function store(): RedirectResponse
    {
        $attributes = request()->validate([
            'question' => [
                'required',
                'min:10',
                new EndWithQuestionMarkRule(),
                new SameQuestionRule(),
            ],
        ]);

        user()->questions()
            ->create([
                'question' => request()->question,
                'draft'    => true,
            ]);

        return back();
    }

    public function Destroy(Question $question): RedirectResponse
    {
        $this->authorize('destroy', $question);
        $question->forceDelete();

        return back();
    }

    public function edit(Question $question): View
    {
        $this->authorize('update', $question);

        return view('question.edit', compact('question'));
    }

    public function update(Question $question): RedirectResponse
    {
        $this->authorize('update', $question);

        request()->validate([
            'question' => [
                'required',
                'min:10',
                new EndWithQuestionMarkRule(),
            ],
        ]);

        $question->question = request()->question;
        $question->save();

        return to_route('question.index');
    }

    public function archive(Question $question): RedirectResponse
    {
        $this->authorize('archive', $question);

        $question->delete();

        return back();
    }

    public function restore(int $id): RedirectResponse
    {
        $question = Question::withTrashed()->findOrFail($id);
        $question->restore();

        return back();
    }
}
