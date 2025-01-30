<?php

// namespace App\Http\Controllers;

// use App\Models\Location;
// use Illuminate\Http\Request;

// class LocationController extends Controller
// {
//     public function index()
//     {
//         return view('location.index');
//     }

//     public function storeLocation(Request $request)
//     {
//         $request->validate([
//             'latitude' => 'required|numeric',
//             'longitude' => 'required|numeric',
//             'vendor_name' => 'required|string'
//         ]);

//         // Save the location
//         Location::create([
//             'latitude' => $request->latitude,
//             'longitude' => $request->longitude,
//             'vendor_name' => $request->vendor_name
//         ]);

//         return response()->json(['message' => 'Location saved successfully']);
//     }
// }



// app/Http/Controllers/LocationController.php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Save the location in the database
        $location = Location::create([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['message' => 'Location saved successfully!']);
    }

    public function getLocations()
    {
        // Get all saved locations
        $locations = Location::all();
        return response()->json($locations);
    }
}
