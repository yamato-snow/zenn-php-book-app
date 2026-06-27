<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ToDo リストの CRUD。
// 「どの URL に来たら、どのメソッドを呼ぶか」を1行ずつ書いている。
Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');          // 一覧
Route::get('/todos/create', [TodoController::class, 'create'])->name('todos.create'); // 作成フォーム
Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');         // 保存
Route::get('/todos/{todo}/edit', [TodoController::class, 'edit'])->name('todos.edit'); // 編集フォーム
Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');  // 更新
Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy'); // 削除
