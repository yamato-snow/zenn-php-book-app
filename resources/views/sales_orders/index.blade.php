@extends('layouts.app')

@section('title', '受注一覧')

@section('content')
    <div class="mb-4">
        <a href="{{ route('sales_orders.create') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ＋ 受注を作成
        </a>
    </div>

    @if ($orders->isEmpty())
        <p class="text-gray-500">まだ受注がありません。「受注を作成」から登録してください。</p>
    @else
        <table class="w-full bg-white rounded shadow text-sm">
            <thead>
                <tr class="border-b text-left text-gray-500">
                    <th class="px-4 py-2">受注番号</th>
                    <th class="px-4 py-2">得意先</th>
                    <th class="px-4 py-2">受注日</th>
                    <th class="px-4 py-2">状態</th>
                    <th class="px-4 py-2 text-right">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="border-b">
                        <td class="px-4 py-2">#{{ $order->id }}</td>
                        <td class="px-4 py-2">{{ $order->partner->name }}</td>
                        <td class="px-4 py-2">{{ $order->order_date->format('Y/m/d') }}</td>
                        <td class="px-4 py-2">
                            @if ($order->status === 'shipped')
                                <span class="text-green-700">{{ $order->statusLabel() }}</span>
                            @else
                                <span class="text-gray-600">{{ $order->statusLabel() }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('sales_orders.show', $order) }}"
                               class="text-blue-600 hover:underline">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
