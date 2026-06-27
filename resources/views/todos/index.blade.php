@extends('layouts.app')

@section('title', 'ToDo リスト')

@section('content')
    <div class="mb-4">
        <a href="{{ route('todos.create') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ＋ 新規追加
        </a>
    </div>

    @if ($todos->isEmpty())
        <p class="text-gray-500">まだ ToDo がありません。「新規追加」から登録してください。</p>
    @else
        <ul class="bg-white rounded shadow divide-y">
            @foreach ($todos as $todo)
                <li class="flex items-center justify-between px-4 py-3">
                    <span class="{{ $todo->done ? 'line-through text-gray-400' : '' }}">
                        {{ $todo->title }}
                    </span>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('todos.edit', $todo) }}"
                           class="text-blue-600 hover:underline">編集</a>

                        {{-- 削除は POST/DELETE で送る必要があるので、リンクではなく小さなフォームにする。 --}}
                        <form action="{{ route('todos.destroy', $todo) }}" method="POST"
                              onsubmit="return confirm('削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">削除</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
