<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

// --- ログインしていなくても開けるページ（認証）---
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- ここから下は、ログインしていないと開けない（auth ミドルウェア）---
Route::middleware('auth')->group(function () {

    // トップページ＝ダッシュボード（既存データの集計を一覧表示）
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ToDo リストの CRUD（Ch06 の練習台）
    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::get('/todos/create', [TodoController::class, 'create'])->name('todos.create');
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::get('/todos/{todo}/edit', [TodoController::class, 'edit'])->name('todos.edit');
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');

    // 商品マスタの CRUD
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // 取引先マスタの CRUD
    Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/create', [PartnerController::class, 'create'])->name('partners.create');
    Route::post('/partners', [PartnerController::class, 'store'])->name('partners.store');
    Route::get('/partners/{partner}/edit', [PartnerController::class, 'edit'])->name('partners.edit');
    Route::put('/partners/{partner}', [PartnerController::class, 'update'])->name('partners.update');
    Route::delete('/partners/{partner}', [PartnerController::class, 'destroy'])->name('partners.destroy');

    // 発注と入荷
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase_orders.index');
    Route::get('/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase_orders.create');
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase_orders.store');
    Route::get('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase_orders.show');
    Route::post('/purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase_orders.receive');

    // 受注と出荷（発注の対称形）
    Route::get('/sales-orders', [SalesOrderController::class, 'index'])->name('sales_orders.index');
    Route::get('/sales-orders/create', [SalesOrderController::class, 'create'])->name('sales_orders.create');
    Route::post('/sales-orders', [SalesOrderController::class, 'store'])->name('sales_orders.store');
    Route::get('/sales-orders/{salesOrder}', [SalesOrderController::class, 'show'])->name('sales_orders.show');
    Route::post('/sales-orders/{salesOrder}/ship', [SalesOrderController::class, 'ship'])->name('sales_orders.ship');

    // 在庫一覧と入出庫履歴
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/stock-transactions', [StockController::class, 'history'])->name('stocks.history');
});
