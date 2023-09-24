<x-app-layout>
    <x-slot name="header">
        <x-header>
            {{ __('Vote for a question') }}
        </x-header>
    </x-slot>

    <x-container>

        <hr class="border-gray-700 border-dashed mt-4 mb-4">

        <div class="dark:text-gray-300 font-bold mb-1 uppercase"> List of Questions </div>

        <div class="dark:text-gray-400 space-y-4">
            @foreach($questions as $item)
                <x-question :question="$item"></x-question>
            @endforeach
        </div>

    </x-container>
</x-app-layout>
