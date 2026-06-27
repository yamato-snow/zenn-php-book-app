<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * 一覧を表示する（GET /products）。
     */
    public function index()
    {
        $products = Product::orderBy('code')->get();

        return view('products.index', ['products' => $products]);
    }

    /**
     * 新規作成フォームを表示する（GET /products/create）。
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * 保存する（POST /products）。
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'       => 'required|max:255|unique:products,code', // 品番は他と重複させない
            'name'       => 'required|max:255',
            'unit_price' => 'required|integer|min:0',                // 単価は0以上の整数
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('message', '商品を登録しました');
    }

    /**
     * 編集フォームを表示する（GET /products/{product}/edit）。
     */
    public function edit(Product $product)
    {
        return view('products.edit', ['product' => $product]);
    }

    /**
     * 更新する（PUT /products/{product}）。
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            // 品番の重複チェック。ただし「自分自身」は除外する（自分と同じ品番でも更新できるように）。
            'code'       => ['required', 'max:255', Rule::unique('products', 'code')->ignore($product->id)],
            'name'       => 'required|max:255',
            'unit_price' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('message', '商品を更新しました');
    }

    /**
     * 削除する（DELETE /products/{product}）。
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('message', '商品を削除しました');
    }
}
