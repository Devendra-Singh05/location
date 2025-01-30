<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;

class ShopController extends Controller {
    
    // वेंडर की दुकान को सेव करने का फ़ंक्शन
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Shop::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['message' => 'Shop location saved successfully!']);
    }

    // सभी वेंडर की लोकेशन को प्राप्त करने का फ़ंक्शन
    public function getShops() {
        return response()->json(Shop::all()); // Shop का नाम, latitude, longitude return करें
    }
}