<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    /**
     * 区分（type）の選択肢。「DB に入れる値 => 画面に出す日本語」の対応表。
     * フォームのセレクトボックスでも、バリデーションでも、この1か所を使い回す。
     */
    public const TYPES = [
        'supplier' => '仕入先',
        'customer' => '得意先',
    ];

    /**
     * フォームからまとめて保存してよい項目。
     */
    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * この取引先の区分を日本語ラベルで返す（例：supplier → 仕入先）。
     */
    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
