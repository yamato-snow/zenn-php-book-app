@extends('layouts.app')

@section('title', '発注 #' . $order->id)

@section('content')
    <div class="bg-white rounded shadow p-4 mb-4">
        <p><span class="text-gray-500">仕入先：</span>{{ $order->partner->name }}</p>
        <p><span class="text-gray-500">発注日：</span>{{ $order->order_date->format('Y/m/d') }}</p>
        <p>
            <span class="text-gray-500">状態：</span>
            @if ($order->status === 'received')
                <span class="text-green-700 font-bold">{{ $order->statusLabel() }}</span>
            @else
                <span class="text-gray-700 font-bold">{{ $order->statusLabel() }}</span>
            @endif
        </p>
    </div>

    <table class="w-full bg-white rounded shadow text-sm mb-4">
        <thead>
            <tr class="border-b text-left text-gray-500">
                <th class="px-4 py-2">商品</th>
                <th class="px-4 py-2 text-right">数量</th>
                <th class="px-4 py-2 text-right">単価</th>
                <th class="px-4 py-2 text-right">小計</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $item->product->code }}：{{ $item->product->name }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($item->quantity) }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($item->unit_price) }} 円</td>
                    <td class="px-4 py-2 text-right">{{ number_format($item->subtotal()) }} 円</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="px-4 py-2 font-bold" colspan="3">合計</td>
                <td class="px-4 py-2 text-right font-bold">
                    {{ number_format($order->items->sum(fn ($item) => $item->subtotal())) }} 円
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="flex items-center gap-3">
        @if ($order->status === 'ordered')
            {{-- 入荷ボタン。押すと在庫が増え、履歴が残る。 --}}
            <form action="{{ route('purchase_orders.receive', $order) }}" method="POST"
                  onsubmit="return confirm('この発注を入荷として処理しますか？（在庫が増えます）');">
                @csrf
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">入荷する</button>
            </form>
        @else
            <span class="text-gray-500">この発注は入荷済みです。</span>
        @endif
        <a href="{{ route('purchase_orders.index') }}" class="text-gray-600 hover:underline">一覧に戻る</a>
    </div>
@endsection
