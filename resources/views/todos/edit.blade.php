@extends('layouts.app')

@section('title', 'ToDo の編集')

@section('content')
    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-2">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('todos.update', $todo) }}" method="POST" class="bg-white rounded shadow p-4">
        @csrf
        @method('PUT') {{-- HTML フォームは PUT を直接送れないので、この1行で「PUT として送る」と伝える。 --}}

        <div class="mb-4">
            <label for="title" class="block mb-1 font-bold">やること</label>
            <input type="text" name="title" id="title"
                   value="{{ old('title', $todo->title) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="done" value="1"
                       {{ $todo->done ? 'checked' : '' }}>
                <span>完了した</span>
            </label>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                更新
            </button>
            <a href="{{ route('todos.index') }}" class="text-gray-600 hover:underline">一覧に戻る</a>
        </div>
    </form>
@endsection
