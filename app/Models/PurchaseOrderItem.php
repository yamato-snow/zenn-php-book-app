<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'unit_price',
    ];

    /**
     * この明細が属する発注（多対1の「多」側）。
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * この明細の商品。
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * 小計（数量 × 単価）。
     */
    public function subtotal(): int
    {
        return $this->quantity * $this->unit_price;
    }
}
