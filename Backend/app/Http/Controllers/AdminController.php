<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tour;
use App\Models\Booking;

class AdminController extends Controller
{
    //thiss all about the Tours

    /**
     * Get all tours (Admin only)
     */

    public function CreateTours(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'destination' => 'required|string',
            'duration_days' => 'required|integer',
            'price_per_person' => 'required|numeric',
            'available_slots' => 'required|integer',
            'image_url' => 'required|url',
        ]);

        $tour = Tour::create($validated);

        return response()->json([
            'message' => 'Tour created successfully',
            'tour' => $tour,
        ], 201);
    }




    public function getAllTours()
    {
        return response()->json(Tour::all(), 200);
    }
    /**
     * Update a tour
     */
    public function updateTour(Request $request, $id)
    {
        $tour = Tour::find($id);
        if (!$tour) {
            return response()->json(['message' => 'Tour not found'], 404);
        }

        $tour->update($request->all());

        return response()->json([
            'message' => 'Tour updated successfully',
            'tour' => $tour
        ], 200);
    }
    /**
     * Delete a tour
     */
    public function deleteTour($id)
    {

        $tour = Tour::find($id);
        if (!$tour) {
            return response()->json(['message' => 'Tour not found'], 404);
        }

        $tour->delete();

        return response()->json(['message' => 'Tour deleted successfully'], 200);
    }
    /////////////////////////////////////////////////////////////////////////////////////////////


    //this all about the user 


    //get all user 
    public function getAllUsers()
    {
        return response()->json(User::all(), 200);
    }

    //delete user 
    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function dashboard()
    {
        return response()->json([
            'message' => 'Welcome to the admin dashboard!!!!!!!!!!!!!!!!!!!!!!!!!',

        ]);
    }



//booking admin contoller (that all about the booking contoller)
    // View all bookings
    public function indexBookings()
    {
        $bookings = Booking::with(['user', 'tour'])->latest()->get();
        return response()->json($bookings);
    }

    // Update booking status (e.g., confirm/cancel)
    public function updateBookingStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,canceled'
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'message' => 'Booking status updated',
            'data' => $booking
        ]);
    }

    // Delete a booking
    public function deleteBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['message' => 'Booking deleted']);
    }
}
