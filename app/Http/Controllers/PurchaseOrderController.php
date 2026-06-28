<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * 発注一覧（GET /purchase-orders）。
     */
    public function index()
    {
        // with('partner') で、一覧表示に使う仕入先もまとめて取ってくる（N+1 問題対策）。
        $orders = PurchaseOrder::with('partner')->orderBy('id', 'desc')->get();

        return view('purchase_orders.index', ['orders' => $orders]);
    }

    /**
     * 発注作成フォーム（GET /purchase-orders/create）。
     */
    public function create()
    {
        // フォームのプルダウンに使う、仕入先と商品の一覧を渡す。
        $suppliers = Partner::where('type', 'supplier')->orderBy('name')->get();
        $products  = Product::orderBy('code')->get();

        return view('purchase_orders.create', [
            'suppliers' => $suppliers,
            'products'  => $products,
        ]);
    }

    /**
     * 発注を保存する（POST /purchase-orders）。
     * ヘッダ（発注）と明細（複数）をまとめて作る。
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'partner_id'          => 'required|exists:partners,id',
            'order_date'          => 'required|date',
            'items'               => 'array',
            'items.*.product_id'  => 'nullable|integer|exists:products,id',
            'items.*.quantity'    => 'nullable|integer|min:1',
        ]);

        // 商品が選ばれている行だけを拾う（空の行は無視する）。
        $rows = [];
        foreach ($request->input('items', []) as $row) {
            if (! empty($row['product_id'])) {
                // 商品は選んだのに数量が空、という行はエラーにする。
                if (empty($row['quantity'])) {
                    return back()->withInput()->with('error', '数量が入力されていない明細があります');
                }
                $rows[] = $row;
            }
        }

        if (count($rows) === 0) {
            return back()->withInput()->with('error', '明細を1行以上入力してください');
        }

        // ヘッダと明細は「まとめて成功・まとめて失敗」させたいので、トランザクションで囲む。
        DB::transaction(function () use ($validated, $rows) {
            $order = PurchaseOrder::create([
                'partner_id' => $validated['partner_id'],
                'order_date' => $validated['order_date'],
                'status'     => 'ordered',
            ]);

            foreach ($rows as $row) {
                // 単価は「発注した時点の商品単価」を控えておく（あとで商品の単価が変わっても発注は変わらない）。
                $product = Product::findOrFail($row['product_id']);

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $row['quantity'],
                    'unit_price' => $product->unit_price,
                ]);
            }
        });

        return redirect()->route('purchase_orders.index')->with('message', '発注を登録しました');
    }

    /**
     * 発注詳細（GET /purchase-orders/{purchaseOrder}）。
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        // 明細と、その商品もまとめて読み込む。
        $purchaseOrder->load('partner', 'items.product');

        return view('purchase_orders.show', ['order' => $purchaseOrder]);
    }

    /**
     * 入荷処理（POST /purchase-orders/{purchaseOrder}/receive）。
     * 在庫を増やし、入出庫履歴を残す。この2つは必ずセットで行う。
     */
    public function receive(PurchaseOrder $purchaseOrder)
    {
        // すでに入荷済みなら、二重に在庫を増やさないように止める。
        if ($purchaseOrder->status === 'received') {
            return back()->with('error', 'この発注はすでに入荷済みです');
        }

        $purchaseOrder->load('items');

        // 「在庫を増やす」と「履歴を残す」を1つのトランザクションにまとめる。
        // 途中で失敗したら、すべてなかったことにする（在庫だけ増えて履歴が無い、を防ぐ）。
        DB::transaction(function () use ($purchaseOrder) {
            foreach ($purchaseOrder->items as $item) {
                // (1) 商品の在庫数を、明細の数量だけ増やす。
                $item->product->increment('stock_quantity', $item->quantity);

                // (2) 入出庫履歴に「入庫」を1行残す。どの発注由来かも記録する。
                StockTransaction::create([
                    'product_id'       => $item->product_id,
                    'type'             => 'in',
                    'quantity'         => $item->quantity,
                    'source_type'      => 'purchase_order',
                    'source_id'        => $purchaseOrder->id,
                    'transaction_date' => now()->toDateString(),
                ]);
            }

            // (3) 発注の状態を「入荷済み」に変える。
            $purchaseOrder->update(['status' => 'received']);
        });

        return redirect()->route('purchase_orders.show', $purchaseOrder)
            ->with('message', '入荷しました。在庫を更新しました');
    }
}
