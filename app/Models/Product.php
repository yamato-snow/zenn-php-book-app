<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * フォームからまとめて保存してよい項目。
     * stock_quantity（在庫数）は入れない。在庫は入荷・出荷の処理でだけ動かすため、
     * 商品マスタのフォームから直接いじれないようにしておく（Ch10・Ch11 で扱う）。
     */
    protected $fillable = [
        'code',
        'name',
        'unit_price',
    ];
}
