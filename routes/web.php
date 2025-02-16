<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SimpleMapController;
use Illuminate\Support\Facades\Route;


// features wala map
// Route::get('/', function () {
//     return view('vendor-location');
// });

// // दुकान की location सेव करने का API
// Route::post('/save-shop', [ShopController::class, 'store']);

// // // सभी दुकानों की लोकेशन प्राप्त करने का API
// Route::get('/get-shops', [ShopController::class, 'getShops']);




//simple map

Route::get('/welcome', function () {
    return view('welcome');
});
Route::post('/save-vendor', [SimpleMapController::class, 'saveLocation'])->name('save.vendor');

Route::get('/', function () {
    return view('form');
});
Route::get('/form', function () {
    return view('form');
})->name('form');