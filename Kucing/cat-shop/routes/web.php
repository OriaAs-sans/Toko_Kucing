<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return redirect()->route('orders.index');
});

// Product CRUD
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WebhookController;

Route::resource('products', ProductController::class);
Route::patch('products/{product}/stock', [ProductController::class, 'adjustStock'])->name('products.adjustStock');
Route::post('products/{product}/image', [ProductController::class, 'replaceImage'])->name('products.replaceImage');
// TikTok sync routes
use App\Http\Controllers\TiktokController;
Route::post('sync/tiktok/products', [TiktokController::class, 'syncProducts'])->name('tiktok.syncProducts');
Route::post('sync/tiktok/orders', [TiktokController::class, 'syncOrders'])->name('tiktok.syncOrders');
Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

// Webhook endpoint (POST) e.g. /webhook/shopee
Route::post('webhook/{channel}', [WebhookController::class, 'receive']);

// Reports
use App\Http\Controllers\ReportController;
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

// Order resource routes for show/edit/update/destroy
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
