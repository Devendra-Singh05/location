<?php

namespace App\Http\Controllers;

use App\Models\SimpleMap;
use Illuminate\Http\Request;

class SimpleMapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function saveLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'number' => 'required',
        ]);

        SimpleMap::create([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'number' => $request->number,
        ]);

        return redirect()->route('form')->with('success', 'your location saved successfully!');
    }
}
