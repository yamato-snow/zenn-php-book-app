<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    /**
     * 入出庫の種別。
     */
    public const TYPES = [
        'in'  => '入庫',
        'out' => '出庫',
    ];

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'source_type',
        'source_id',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    /**
     * この履歴の商品。
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * 種別を日本語ラベルで返す（in → 入庫 / out → 出庫）。
     */
    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * この履歴の由来（どの伝票から来たか）を日本語で返す。
     * 例：発注 #3 / 受注 #5
     */
    public function sourceLabel(): string
    {
        if ($this->source_type === 'purchase_order') {
            return '発注 #' . $this->source_id;
        }
        if ($this->source_type === 'sales_order') {
            return '受注 #' . $this->source_id;
        }
        return '—';
    }
}
