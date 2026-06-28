@extends('layouts.app')

@section('title', '在庫一覧')

@section('content')
    {{-- 検索・絞り込みフォーム。GET なので、条件が URL に乗る（共有・再表示しやすい）。 --}}
    <form action="{{ route('stocks.index') }}" method="GET" class="bg-white rounded shadow p-4 mb-4">
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label for="keyword" class="block mb-1 text-sm text-gray-600">品番・品名で検索</label>
                <input type="text" name="keyword" id="keyword"
                       value="{{ $keyword }}"
                       class="border rounded px-3 py-2" placeholder="例：A-001 / ボールペン">
            </div>
            <div>
                <label for="stock" class="block mb-1 text-sm text-gray-600">在庫</label>
                <select name="stock" id="stock" class="border rounded px-3 py-2">
                    <option value="" {{ $stock === '' || $stock === null ? 'selected' : '' }}>すべて</option>
                    <option value="in" {{ $stock === 'in' ? 'selected' : '' }}>在庫あり</option>
                    <option value="zero" {{ $stock === 'zero' ? 'selected' : '' }}>在庫なし</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">検索</button>
                <a href="{{ route('stocks.index') }}" class="text-gray-600 hover:underline">クリア</a>
            </div>
        </div>
    </form>

    @if ($products->isEmpty())
        <p class="text-gray-500">該当する商品がありません。</p>
    @else
        <table class="w-full bg-white rounded shadow text-sm">
            <thead>
                <tr class="border-b text-left text-gray-500">
                    <th class="px-4 py-2">品番</th>
                    <th class="px-4 py-2">品名</th>
                    <th class="px-4 py-2 text-right">現在庫</th>
                    <th class="px-4 py-2 text-right">履歴</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $product->code }}</td>
                        <td class="px-4 py-2">{{ $product->name }}</td>
                        <td class="px-4 py-2 text-right">
                            @if ($product->stock_quantity <= 0)
                                <span class="text-red-600 font-bold">{{ number_format($product->stock_quantity) }}</span>
                            @else
                                {{ number_format($product->stock_quantity) }}
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('stocks.history', ['product_id' => $product->id]) }}"
                               class="text-blue-600 hover:underline">入出庫履歴</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
