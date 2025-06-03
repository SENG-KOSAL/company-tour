<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;

class TourContoller extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'description' => 'required|string',
            'title' => 'required|string|max:255',
            'destination' => 'required|string',
            'duration_days' => 'required|integer',
            'price_per_person' => 'required|numeric',
            'available_slots' => 'required|integer',
            'image_url' => 'nullable|url', // Make sure the image_url is valid URL
        ]);

        // Insert into database
        $tour = Tour::create([
            'title' => $request->title,
            'description' => $request->description,
            'destination' => $request->destination,
            'duration_days' => $request->duration_days,
            'price_per_person' => $request->price_per_person,
            'available_slots' => $request->available_slots,
            'image_url' => $request->image_url, 
        ]);
        return response()->json([
            'message' => 'Tour created successfully!',
            'tour' => $tour
        ], 201);
    }
    public function getTours()
    {
        $tours = Tour::all(); // Fetch all tour records

        return response()->json([
            'message' => 'Tours retrieved successfully!',
            'tours' => $tours
        ], 200);
    }   
    public function getTours4(Request $request)
    {
        $limit = $request->query('limit'); // Default to 4 if not provided    ,4 

        $tours = Tour::paginate($limit); // Use pagination

        return response()->json($tours); // Laravel automatically formats pagination
    }


    public function getTourById($id)
    {
        $tour = Tour::find($id);

        if (!$tour) {
            return response()->json(['message' => 'Tour not found'], 404);
        }

        return response()->json($tour);
    }
}
