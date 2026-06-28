<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
    protected $fillable = [
        'sales_order_id',
        'product_id',
        'quantity',
        'unit_price',
    ];

    /**
     * この明細が属する受注。
     */
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
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
