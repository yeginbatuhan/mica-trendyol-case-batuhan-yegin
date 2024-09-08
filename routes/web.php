<?php

use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\service\trendyol\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\log\LogController;


Route::get('/', [HomePage::class, 'index'])->name('pages-home');
Route::controller(ProductController::class)->group(function () {
  Route::get('/fetch-products', 'fetchProducts');
  Route::get('/products', 'index')->name('products.index');
  Route::post('/products/update/{id}', 'update')->name('products.update');
});
Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
