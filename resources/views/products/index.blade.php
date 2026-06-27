@extends('layouts.app')

@section('title', '商品マスタ')

@section('content')
    <div class="mb-4">
        <a href="{{ route('products.create') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ＋ 商品を登録
        </a>
    </div>

    @if ($products->isEmpty())
        <p class="text-gray-500">まだ商品がありません。「商品を登録」から追加してください。</p>
    @else
        <table class="w-full bg-white rounded shadow text-sm">
            <thead>
                <tr class="border-b text-left text-gray-500">
                    <th class="px-4 py-2">品番</th>
                    <th class="px-4 py-2">品名</th>
                    <th class="px-4 py-2 text-right">単価</th>
                    <th class="px-4 py-2 text-right">現在庫</th>
                    <th class="px-4 py-2 text-right">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $product->code }}</td>
                        <td class="px-4 py-2">{{ $product->name }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($product->unit_price) }} 円</td>
                        <td class="px-4 py-2 text-right">{{ number_format($product->stock_quantity) }}</td>
                        <td class="px-4 py-2 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('products.edit', $product) }}"
                                   class="text-blue-600 hover:underline">編集</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">削除</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
