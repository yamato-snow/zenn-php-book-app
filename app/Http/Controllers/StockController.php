<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * 在庫一覧（GET /stocks）。
     * 品番・品名での検索と、在庫あり／なしの絞り込みができる。
     */
    public function index(Request $request)
    {
        // 検索キーワードと絞り込み条件を受け取る（無ければ null / 空文字）。
        $keyword = $request->input('keyword');
        $stock   = $request->input('stock'); // ''=すべて / 'in'=在庫あり / 'zero'=在庫なし

        $products = Product::query()
            // キーワードがあるときだけ、品番 or 品名で部分一致検索する。
            ->when($keyword, function ($query, $keyword) {
                // (code LIKE ... OR name LIKE ...) をカッコでまとめる。
                $query->where(function ($q) use ($keyword) {
                    $q->where('code', 'like', "%{$keyword}%")
                      ->orWhere('name', 'like', "%{$keyword}%");
                });
            })
            // 在庫ありだけ
            ->when($stock === 'in', function ($query) {
                $query->where('stock_quantity', '>', 0);
            })
            // 在庫なし（0以下）だけ
            ->when($stock === 'zero', function ($query) {
                $query->where('stock_quantity', '<=', 0);
            })
            ->orderBy('code')
            ->get();

        return view('stocks.index', [
            'products' => $products,
            'keyword'  => $keyword,
            'stock'    => $stock,
        ]);
    }

    /**
     * 入出庫履歴（GET /stock-transactions）。
     * 商品で絞り込める。新しい順に表示する。
     */
    public function history(Request $request)
    {
        $productId = $request->input('product_id');

        $transactions = StockTransaction::query()
            ->with('product') // 一覧で商品名を出すのでまとめ読み（N+1 対策）
            ->when($productId, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // 絞り込み用のプルダウンに使う商品一覧。
        $products = Product::orderBy('code')->get();

        return view('stocks.history', [
            'transactions' => $transactions,
            'products'     => $products,
            'productId'    => $productId,
        ]);
    }
}
