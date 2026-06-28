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
}
