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
    {{-- ログインしているときだけ、上部にナビゲーションを表示する。 --}}
    @auth
        <nav class="bg-white border-b">
            <div class="max-w-5xl mx-auto px-6 py-3 flex flex-wrap items-center gap-4 text-sm">
                <a href="{{ route('dashboard') }}" class="font-bold">在庫管理システム</a>
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">ダッシュボード</a>
                <a href="{{ route('stocks.index') }}" class="text-blue-600 hover:underline">在庫</a>
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">商品</a>
                <a href="{{ route('partners.index') }}" class="text-blue-600 hover:underline">取引先</a>
                <a href="{{ route('purchase_orders.index') }}" class="text-blue-600 hover:underline">発注</a>
                <a href="{{ route('sales_orders.index') }}" class="text-blue-600 hover:underline">受注</a>
                <a href="{{ route('stocks.history') }}" class="text-blue-600 hover:underline">入出庫履歴</a>
                <div class="ml-auto flex items-center gap-3">
                    <span class="text-gray-500">{{ auth()->user()->name }} さん</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">ログアウト</button>
                    </form>
                </div>
            </div>
        </nav>
    @endauth

    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">@yield('title', '在庫管理システム')</h1>

        {{-- 「追加しました」などの完了メッセージを表示する。session に message があるときだけ出す。 --}}
        @if (session('message'))
            <div class="mb-4 rounded bg-green-100 border border-green-300 text-green-800 px-4 py-2">
                {{ session('message') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-2">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
