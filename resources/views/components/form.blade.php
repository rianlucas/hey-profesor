@props([
    'action',
    'post' => null,
    'put' => null,
    'delete' => null
])



<form action="{{ route('question.store') }}" method="post">
    @csrf

    @if($put)
        @method('PUT')
    @endif

    @if($delete)
        @method('DELETE')
    @endif

    <x-textarea label="Question" name="question"></x-textarea>
    {{ $slot }}
</form>
