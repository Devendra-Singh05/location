<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add-location', function () {
    return view('vendor-location');
})->name('pagal');

// Route::get('/save-location', [LocationController::class, 'store']);
// Route::get('/get-locations', [LocationController::class, 'getLocations']);
// Route::get('/', [LocationController::class, 'index']);
// Route::post('/store-location', [LocationController::class, 'storeLocation']);




// दुकान की location सेव करने का API
Route::post('/save-shop', [ShopController::class, 'store']);

// // सभी दुकानों की लोकेशन प्राप्त करने का API
Route::get('/get-shops', [ShopController::class, 'getShops']);

// वेंडर का लोकेशन पेज दिखाने के लिए
// Route::get('/', function () {
//     return view('vendor-location');
// });