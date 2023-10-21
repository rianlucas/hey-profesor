<x-app-layout>
    <x-slot name="header">
        <x-header>
            {{ __('My Questions') }}
        </x-header>
    </x-slot>

    <x-container>
        <x-form post :action="route('question.store')">
            <x-textarea label="Question" name="question"></x-textarea>

            <x-btn.primary type="submit">Save</x-btn.primary>
            <x-btn.reset type="reset">Cancel</x-btn.reset>

        </x-form>

        <hr class="border-gray-700 border-dashed mt-4 mb-4">

        <div class="dark:text-gray-300 font-bold mb-1 uppercase">
            Drafts
        </div>

        <div class="dark:text-gray-400 space-y-4">

            <x-table>
                <x-table.thead>
                    <tr>
                        <x-table.th> Question </x-table.th>
                        <x-table.th> Actions </x-table.th>
                    </tr>
                </x-table.thead>
                <tbody>

                    @foreach($questions->where('draft', '=', true) as $question)
                        <x-table.tr>
                            <x-table.td>{{ $question->question }}</x-table.td>
                            <x-table.td>

                                <x-form :action="route('question.publish', $question)" put >
                                    <button type="submit" class="hover:underline text-blue-500">
                                        Publish
                                    </button>
                                </x-form>

                                <x-form :action="route('question.destroy', $question)" delete onsubmit="return confirm('Tem certeza?')">
                                    <button type="submit" class="hover:underline text-blue-500">
                                        Delete
                                    </button>
                                </x-form>

                                <a href="{{ route('question.edit', $question) }} " class="hover:underline text-blue-500">  Edit </a>

                            </x-table.td>
                        </x-table.tr>
                    @endforeach

                </tbody>
            </x-table>


            <div class="dark:text-gray-300 font-bold mb-1 uppercase">
                Archived Questions
            </div>

            <div class="dark:text-gray-400 space-y-4">

                <x-table>
                    <x-table.thead>
                        <tr>
                            <x-table.th> Question </x-table.th>
                            <x-table.th> Actions </x-table.th>
                        </tr>
                    </x-table.thead>
                    <tbody>

                    @foreach($archivedQuestion->where('draft', '=', false) as $question)
                        <x-table.tr>
                            <x-table.td>{{ $question->question }}</x-table.td>
                            <x-table.td>

                                <x-form :action="route('question.restore', $question)" patch >
                                    <button type="submit" class="hover:underline text-blue-500">
                                        Restore
                                    </button>
                                </x-form>


                            </x-table.td>
                        </x-table.tr>
                    @endforeach

                    </tbody>
                </x-table>


            <div class="dark:text-gray-300 font-bold mb-1 uppercase">
                My Questions
            </div>


            <div class="dark:text-gray-400 space-y-4">

                <x-table>
                    <x-table.thead>
                        <tr>
                            <x-table.th> Question </x-table.th>
                            <x-table.th> Actions </x-table.th>
                        </tr>
                    </x-table.thead>
                    <tbody>

                    @foreach($questions->where('draft', '=', false) as $question)
                        <x-table.tr>
                            <x-table.td>{{ $question->question }}</x-table.td>
                            <x-table.td>

                                <x-form :action="route('question.destroy', $question)" delete onsubmit="return confirm('Tem certeza?')">
                                    <button type="submit" class="hover:underline text-blue-500">
                                        Delete
                                    </button>
                                </x-form>

                                <x-form :action="route('question.archive', $question)" patch >
                                    <button type="submit" class="hover:underline text-blue-500">
                                        Archive
                                    </button>
                                </x-form>

                            </x-table.td>
                        </x-table.tr>
                    @endforeach

                    </tbody>
                </x-table>


            @foreach($questions as $item)
                <x-question :question="$item"></x-question>
            @endforeach
        </div>

    </x-container>
</x-app-layout>
