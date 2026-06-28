@extends('layouts.app')

@section('title', 'ログイン')

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

    <form action="{{ route('login') }}" method="POST" class="bg-white rounded shadow p-4 max-w-md">
        @csrf

        <div class="mb-4">
            <label for="email" class="block mb-1 font-bold">メールアドレス</label>
            <input type="email" name="email" id="email"
                   value="{{ old('email') }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="password" class="block mb-1 font-bold">パスワード</label>
            <input type="password" name="password" id="password"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="remember" value="1">
                <span>ログインしたままにする</span>
            </label>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">ログイン</button>
            <a href="{{ route('register') }}" class="text-gray-600 hover:underline">新規登録はこちら</a>
        </div>
    </form>
@endsection
