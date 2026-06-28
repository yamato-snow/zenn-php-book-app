@extends('layouts.app')

@section('title', '受注の作成')

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

    @if ($customers->isEmpty())
        <p class="text-red-600">得意先が登録されていません。先に「取引先マスタ」で得意先を登録してください。</p>
    @elseif ($products->isEmpty())
        <p class="text-red-600">商品が登録されていません。先に「商品マスタ」で商品を登録してください。</p>
    @else
        <form action="{{ route('sales_orders.store') }}" method="POST" class="bg-white rounded shadow p-4">
            @csrf

            <div class="mb-4">
                <label for="partner_id" class="block mb-1 font-bold">得意先</label>
                <select name="partner_id" id="partner_id" class="w-full border rounded px-3 py-2">
                    <option value="">選択してください</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ (string) old('partner_id') === (string) $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="order_date" class="block mb-1 font-bold">受注日</label>
                <input type="date" name="order_date" id="order_date"
                       value="{{ old('order_date', date('Y-m-d')) }}"
                       class="border rounded px-3 py-2">
            </div>

            <p class="font-bold mb-2">明細（最大3行・使う行だけ入力）</p>
            <table class="w-full mb-4 text-sm">
                <thead>
                    <tr class="text-left text-gray-500">
                        <th class="py-1">商品（かっこ内は現在庫）</th>
                        <th class="py-1 w-32">数量</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 3; $i++)
                        <tr>
                            <td class="py-1 pr-2">
                                <select name="items[{{ $i }}][product_id]" class="w-full border rounded px-2 py-1">
                                    <option value="">（選択しない）</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ (string) old("items.$i.product_id") === (string) $product->id ? 'selected' : '' }}>
                                            {{ $product->code }}：{{ $product->name }}（在庫 {{ number_format($product->stock_quantity) }}）
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-1">
                                <input type="number" name="items[{{ $i }}][quantity]"
                                       value="{{ old("items.$i.quantity") }}"
                                       class="w-full border rounded px-2 py-1" placeholder="例：3">
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">受注する</button>
                <a href="{{ route('sales_orders.index') }}" class="text-gray-600 hover:underline">一覧に戻る</a>
            </div>
        </form>
    @endif
@endsection
