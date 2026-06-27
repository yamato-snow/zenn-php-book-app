@extends('layouts.app')

@section('title', 'ToDo の新規追加')

@section('content')
    {{-- 入力エラーがあれば、まとめて赤字で表示する。 --}}
    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-2">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('todos.store') }}" method="POST" class="bg-white rounded shadow p-4">
        @csrf {{-- 不正なフォーム送信を防ぐための「合言葉」。POST フォームには必ず付ける。 --}}

        <div class="mb-4">
            <label for="title" class="block mb-1 font-bold">やること</label>
            <input type="text" name="title" id="title"
                   value="{{ old('title') }}"
                   class="w-full border rounded px-3 py-2"
                   placeholder="例：牛乳を買う">
        </div>

        <div class="flex items-center gap-2">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                保存
            </button>
            <a href="{{ route('todos.index') }}" class="text-gray-600 hover:underline">一覧に戻る</a>
        </div>
    </form>
@endsection
