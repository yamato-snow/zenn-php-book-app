<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\StockTransaction;

class DashboardController extends Controller
{
    /**
     * ダッシュボード（GET /）。
     * 新しいテーブルは作らず、これまでに貯めたデータを「数える」だけ。
     * 業務システムのトップでよく見る「今どうなっているか」の要約画面を作る。
     */
    public function index()
    {
        // 総商品数（products テーブルの件数）。
        $productCount = Product::count();

        // 在庫切れの商品数（在庫が 0 以下のもの）。
        $outOfStockCount = Product::where('stock_quantity', '<=', 0)->count();

        // 未入荷の発注数（status が ordered ＝まだ入荷していない発注）。
        $pendingPurchaseCount = PurchaseOrder::where('status', 'ordered')->count();

        // 未出荷の受注数（status が ordered ＝まだ出荷していない受注）。
        $pendingSalesCount = SalesOrder::where('status', 'ordered')->count();

        // 最近の入出庫履歴を 5 件だけ。商品名も出すのでまとめ読み（N+1 対策）。
        $recentTransactions = StockTransaction::with('product')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'productCount'         => $productCount,
            'outOfStockCount'      => $outOfStockCount,
            'pendingPurchaseCount' => $pendingPurchaseCount,
            'pendingSalesCount'    => $pendingSalesCount,
            'recentTransactions'   => $recentTransactions,
        ]);
    }
}
