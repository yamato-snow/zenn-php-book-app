<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    /**
     * 状態（status）の選択肢。「DB の値 => 画面の日本語」。
     */
    public const STATUSES = [
        'ordered'  => '発注済み',
        'received' => '入荷済み',
    ];

    protected $fillable = [
        'partner_id',
        'order_date',
        'status',
    ];

    /**
     * order_date を日付として扱えるようにする。
     */
    protected $casts = [
        'order_date' => 'date',
    ];

    /**
     * この発注の仕入先（1件の発注は1つの取引先に属する）。
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * この発注の明細（1件の発注は複数の明細を持つ ＝ 1対多）。
     */
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * 状態を日本語ラベルで返す。
     */
    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
