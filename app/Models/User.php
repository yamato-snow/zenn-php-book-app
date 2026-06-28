<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * まとめて保存してよい項目。
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * 画面やJSONに出さない項目（パスワードは外に出さない）。
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 値の変換。'password' => 'hashed' で、保存時に自動でハッシュ化される。
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];
}
