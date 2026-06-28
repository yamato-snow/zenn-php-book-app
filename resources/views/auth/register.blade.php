@extends('layouts.app')

@section('title', '新規登録')

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

    <form action="{{ route('register') }}" method="POST" class="bg-white rounded shadow p-4 max-w-md">
        @csrf

        <div class="mb-4">
            <label for="name" class="block mb-1 font-bold">名前</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name') }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="email" class="block mb-1 font-bold">メールアドレス</label>
            <input type="email" name="email" id="email"
                   value="{{ old('email') }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="password" class="block mb-1 font-bold">パスワード（8文字以上）</label>
            <input type="password" name="password" id="password"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block mb-1 font-bold">パスワード（確認）</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">登録する</button>
            <a href="{{ route('login') }}" class="text-gray-600 hover:underline">ログインはこちら</a>
        </div>
    </form>
@endsection
