@extends('layouts.app')

@section('title', '商品の編集')

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

    <form action="{{ route('products.update', $product) }}" method="POST" class="bg-white rounded shadow p-4">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="code" class="block mb-1 font-bold">品番</label>
            <input type="text" name="code" id="code"
                   value="{{ old('code', $product->code) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="name" class="block mb-1 font-bold">品名</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', $product->name) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="unit_price" class="block mb-1 font-bold">単価（円）</label>
            <input type="number" name="unit_price" id="unit_price"
                   value="{{ old('unit_price', $product->unit_price) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        {{-- 現在庫はここでは編集しない。入荷・出荷（Ch10・Ch11）でだけ動かすため、表示のみ。 --}}
        <div class="mb-4">
            <span class="block mb-1 font-bold">現在庫</span>
            <p class="text-gray-700">{{ number_format($product->stock_quantity) }}
                <span class="text-gray-400 text-sm">（在庫は入荷・出荷で増減します）</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">更新</button>
            <a href="{{ route('products.index') }}" class="text-gray-600 hover:underline">一覧に戻る</a>
        </div>
    </form>
@endsection
