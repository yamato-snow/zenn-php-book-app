@extends('layouts.app')

@section('title', '入出庫履歴')

@section('content')
    <form action="{{ route('stocks.history') }}" method="GET" class="bg-white rounded shadow p-4 mb-4">
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label for="product_id" class="block mb-1 text-sm text-gray-600">商品で絞り込む</label>
                <select name="product_id" id="product_id" class="border rounded px-3 py-2">
                    <option value="">すべての商品</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                            {{ (string) $productId === (string) $product->id ? 'selected' : '' }}>
                            {{ $product->code }}：{{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">絞り込む</button>
                <a href="{{ route('stocks.history') }}" class="text-gray-600 hover:underline">クリア</a>
            </div>
        </div>
    </form>

    @if ($transactions->isEmpty())
        <p class="text-gray-500">入出庫の履歴がありません。発注の入荷・受注の出荷を行うと記録されます。</p>
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
                @foreach ($transactions as $tx)
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
