<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    /**
     * 受注一覧（GET /sales-orders）。
     */
    public function index()
    {
        $orders = SalesOrder::with('partner')->orderBy('id', 'desc')->get();

        return view('sales_orders.index', ['orders' => $orders]);
    }

    /**
     * 受注作成フォーム（GET /sales-orders/create）。
     */
    public function create()
    {
        // 得意先と商品の一覧をプルダウン用に渡す。
        $customers = Partner::where('type', 'customer')->orderBy('name')->get();
        $products  = Product::orderBy('code')->get();

        return view('sales_orders.create', [
            'customers' => $customers,
            'products'  => $products,
        ]);
    }

    /**
     * 受注を保存する（POST /sales-orders）。発注の store とほぼ同じ。
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'partner_id'         => 'required|exists:partners,id',
            'order_date'         => 'required|date',
            'items'              => 'array',
            'items.*.product_id' => 'nullable|integer|exists:products,id',
            'items.*.quantity'   => 'nullable|integer|min:1',
        ]);

        $rows = [];
        foreach ($request->input('items', []) as $row) {
            if (! empty($row['product_id'])) {
                if (empty($row['quantity'])) {
                    return back()->withInput()->with('error', '数量が入力されていない明細があります');
                }
                $rows[] = $row;
            }
        }

        if (count($rows) === 0) {
            return back()->withInput()->with('error', '明細を1行以上入力してください');
        }

        DB::transaction(function () use ($validated, $rows) {
            $order = SalesOrder::create([
                'partner_id' => $validated['partner_id'],
                'order_date' => $validated['order_date'],
                'status'     => 'ordered',
            ]);

            foreach ($rows as $row) {
                $product = Product::findOrFail($row['product_id']);

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $row['quantity'],
                    'unit_price' => $product->unit_price,
                ]);
            }
        });

        return redirect()->route('sales_orders.index')->with('message', '受注を登録しました');
    }

    /**
     * 受注詳細（GET /sales-orders/{salesOrder}）。
     */
    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load('partner', 'items.product');

        return view('sales_orders.show', ['order' => $salesOrder]);
    }

    /**
     * 出荷処理（POST /sales-orders/{salesOrder}/ship）。
     * 在庫を減らし、入出庫履歴を残す。在庫が足りなければ出荷しない。
     */
    public function ship(SalesOrder $salesOrder)
    {
        if ($salesOrder->status === 'shipped') {
            return back()->with('error', 'この受注はすでに出荷済みです');
        }

        $salesOrder->load('items.product');

        // ★在庫不足チェック：1品目でも足りなければ、出荷自体を行わない。
        $shortages = [];
        foreach ($salesOrder->items as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                $shortages[] = "{$item->product->name}（在庫 {$item->product->stock_quantity} / 必要 {$item->quantity}）";
            }
        }

        if (count($shortages) > 0) {
            return back()->with('error', '在庫が不足しているため出荷できません：' . implode('、', $shortages));
        }

        // 「在庫を減らす」と「履歴を残す」をまとめて行う。
        DB::transaction(function () use ($salesOrder) {
            foreach ($salesOrder->items as $item) {
                // (1) 在庫を減らす。
                $item->product->decrement('stock_quantity', $item->quantity);

                // (2) 履歴を残す（出庫）。どの受注由来かも記録する。
                StockTransaction::create([
                    'product_id'       => $item->product_id,
                    'type'             => 'out',
                    'quantity'         => $item->quantity,
                    'source_type'      => 'sales_order',
                    'source_id'        => $salesOrder->id,
                    'transaction_date' => now()->toDateString(),
                ]);
            }

            // (3) 受注を「出荷済み」にする。
            $salesOrder->update(['status' => 'shipped']);
        });

        return redirect()->route('sales_orders.show', $salesOrder)
            ->with('message', '出荷しました。在庫を更新しました');
    }
}
