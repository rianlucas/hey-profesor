<x-app-layout>
    <x-slot name="header">
        <x-header>
            {{ __('Edit Question') }} :: {{ $question->id }}
        </x-header>
    </x-slot>

    <x-container>
        <x-form post :action="route('question.update', $question)" put>
            <x-textarea label="Question" name="question" :value="$question->question"></x-textarea>

            <x-btn.primary type="submit">Save</x-btn.primary>
            <x-btn.reset type="reset">Cancel</x-btn.reset>

        </x-form>
    </x-container>

</x-app-layout>
