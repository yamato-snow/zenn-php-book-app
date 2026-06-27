<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    /**
     * フォームからまとめて保存（create / update）してよい項目。
     * ここに書いた項目だけが $request->all() などで一括代入できる。
     */
    protected $fillable = [
        'title',
        'done',
    ];

    /**
     * DB では 0/1 で持っている done を、PHP 側では true/false として扱えるようにする。
     */
    protected $casts = [
        'done' => 'boolean',
    ];
}
