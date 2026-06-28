@extends('layouts.app')

@section('title', 'ダッシュボード')

@section('content')
    {{-- 4つの数字カード。2列に並べる（スマホでは1列）。 --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        {{-- 総商品数 --}}
        <a href="{{ route('products.index') }}" class="block bg-white rounded shadow p-4 hover:bg-gray-50">
            <div class="text-sm text-gray-500">総商品数</div>
            <div class="text-3xl font-bold">{{ number_format($productCount) }}</div>
        </a>

        {{-- 在庫切れ件数（0件なら灰色、1件以上なら赤で目立たせる）--}}
        <a href="{{ route('stocks.index', ['stock' => 'zero']) }}" class="block bg-white rounded shadow p-4 hover:bg-gray-50">
            <div class="text-sm text-gray-500">在庫切れ</div>
            <div class="text-3xl font-bold {{ $outOfStockCount > 0 ? 'text-red-600' : 'text-gray-400' }}">
                {{ number_format($outOfStockCount) }}
            </div>
        </a>

        {{-- 未入荷の発注数 --}}
        <a href="{{ route('purchase_orders.index') }}" class="block bg-white rounded shadow p-4 hover:bg-gray-50">
            <div class="text-sm text-gray-500">未入荷の発注</div>
            <div class="text-3xl font-bold {{ $pendingPurchaseCount > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                {{ number_format($pendingPurchaseCount) }}
            </div>
        </a>

        {{-- 未出荷の受注数 --}}
        <a href="{{ route('sales_orders.index') }}" class="block bg-white rounded shadow p-4 hover:bg-gray-50">
            <div class="text-sm text-gray-500">未出荷の受注</div>
            <div class="text-3xl font-bold {{ $pendingSalesCount > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                {{ number_format($pendingSalesCount) }}
            </div>
        </a>
    </div>

    {{-- 最近の入出庫履歴 --}}
    <div class="flex items-center justify-between mb-2">
        <h2 class="text-lg font-bold">最近の入出庫</h2>
        <a href="{{ route('stocks.history') }}" class="text-sm text-blue-600 hover:underline">すべて見る</a>
    </div>

    @if ($recentTransactions->isEmpty())
        <p class="text-gray-500">入出庫の履歴がまだありません。発注の入荷・受注の出荷を行うと記録されます。</p>
    @else
        <table class="w-full bg-white rounded shadow text-sm">
            <thead>
                <tr class="border-b text-left text-gray-500">
                    <th class="px-4 py-2">日付</th>
                    <th class="px-4 py-2">商品</th>
                    <th class="px-4 py-2">種別</th>
                    <th class="px-4 py-2 text-right">数量</th>
                    <th class="px-4 py-2">由来</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentTransactions as $tx)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $tx->transaction_date->format('Y/m/d') }}</td>
                        <td class="px-4 py-2">{{ $tx->product->code }}：{{ $tx->product->name }}</td>
                        <td class="px-4 py-2">
                            @if ($tx->type === 'in')
                                <span class="text-green-700">{{ $tx->typeLabel() }}</span>
                            @else
                                <span class="text-orange-700">{{ $tx->typeLabel() }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            {{ $tx->type === 'in' ? '+' : '−' }}{{ number_format($tx->quantity) }}
                        </td>
                        <td class="px-4 py-2 text-gray-600">{{ $tx->sourceLabel() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
