<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '在庫管理システム')</title>
    {{-- 学習用に Tailwind の CDN を使う。npm のビルドが要らず、コピペですぐ見た目が整う。 --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">@yield('title', '在庫管理システム')</h1>

        {{-- 「追加しました」などの完了メッセージを表示する。session に message があるときだけ出す。 --}}
        @if (session('message'))
            <div class="mb-4 rounded bg-green-100 border border-green-300 text-green-800 px-4 py-2">
                {{ session('message') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
