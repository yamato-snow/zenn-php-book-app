<?php

use App\Http\Controllers\ProductController;
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

// 商品マスタの CRUD（Ch06 の ToDo とまったく同じ型）
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
