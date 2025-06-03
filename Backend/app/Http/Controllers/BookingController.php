<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class BookingController extends Controller
{
    // Create a new booking for a user and a tour
    public function createBooking(Request $request)
    {
        // Validate input
        $request->validate([
            // 'user_id' => 'required|exists:users,id',   // Ensure the user exists
            'tour_id' => 'required|exists:tours,tour_id',  // Ensure the tour exists
            'status' => 'in:pending,confirmed,canceled', // Valid status values
            'number_of_people' => 'required|integer|min:1', // Ensure there is at least one person
            'booking_date' => 'nullable|date', // Optional booking date, defaults to current date
        ]);

        // Get the user and tour from the database
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return DB::transaction(function () use ($request, $user) {
            // Lock the tour row to prevent race condition
            $tour = Tour::lockForUpdate()->find($request->tour_id);

            if (!$tour) {
                return response()->json(['message' => 'Tour not found'], 404);
            }



            // 🚨 Check if enough available slots for requested number of people
            if ($request->number_of_people > $tour->available_slots) {
                return response()->json([
                    'message' => 'Not enough available slots. Only ' . $tour->available_slots . ' left.'
                ], 400);
            }



            // Create the booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'tour_id' => $tour->tour_id,
                'status' => $request->status ?? 'pending',  // Default to 'pending' if no status is provided
                'number_of_people' => $request->number_of_people,
                'booking_date' => $request->booking_date ?? now(), // Default to current date if no date is provided
            ]);

            // 🧮 Update tour's available slots
            $tour->available_slots -= $request->number_of_people;
            $tour->save();
            return response()->json(['message' => 'Booking created successfully!', 'booking' => $booking], 201);
        });
    }

    // Get all bookings
    public function getBookings()
    {
        $bookings = Booking::with(['user', 'tour'])->get();  // Get bookings with user and tour relations
        return response()->json(['bookings' => $bookings]);
    }

    // Get a specific booking by ID
    public function getBookingById($id)
    {
        $booking = Booking::with(['user', 'tour'])->find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json(['booking' => $booking]);
    }

    // Update a booking (e.g., changing status or number of people)
    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Validate input for updating booking
        $request->validate([
            'status' => 'in:pending,confirmed,canceled',
            'number_of_people' => 'integer|min:1',
        ]);

        // Update the booking details
        $booking->update([
            'status' => $request->status ?? $booking->status,
            'number_of_people' => $request->number_of_people ?? $booking->number_of_people,
        ]);

        return response()->json(['message' => 'Booking updated successfully!', 'booking' => $booking]);
    }

    // Cancel a booking
    public function cancelBooking($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Set status to canceled
        $booking->update(['status' => 'canceled']);

        return response()->json(['message' => 'Booking canceled successfully!', 'booking' => $booking]);
    }
}
