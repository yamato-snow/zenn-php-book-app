<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    /**
     * 状態（status）の選択肢。「DB の値 => 画面の日本語」。
     */
    public const STATUSES = [
        'ordered' => '受注済み',
        'shipped' => '出荷済み',
    ];

    protected $fillable = [
        'partner_id',
        'order_date',
        'status',
    ];

    protected $casts = [
        'order_date' => 'date',
    ];

    /**
     * この受注の得意先（受注 → 1つの取引先）。
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * この受注の明細（受注 → 複数の明細 ＝ 1対多）。
     */
    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
