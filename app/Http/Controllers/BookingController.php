<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['event', 'event.place', 'event.organizer', 'explorer'])
            ->when($request->search, function($query, $search) {
                $query->whereHas('event', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })
                ->orWhereHas('explorer', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'bookings' => $bookings->items(),
                'pagination' => [
                    'total' => $bookings->total(),
                    'per_page' => $bookings->perPage(),
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                ]
            ]);
        }

        return view('booking.index', compact('bookings'));
    }

    public function getExplorerBookings(User $explorer)
    {
        if ($explorer->role !== 'explorer') {
            return response()->json(['error' => 'User is not an explorer'], 403);
        }

        $bookings = $explorer->bookings()
            ->with(['event', 'event.place', 'event.organizer'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'bookings' => $bookings->items(),
            'pagination' => [
                'total' => $bookings->total(),
                'per_page' => $bookings->perPage(),
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
            ]
        ]);
    }

    public function getBookingDetails(Booking $booking)
    {
        $booking->load(['event', 'event.place', 'event.organizer', 'explorer']);
        return response()->json($booking);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        $booking->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully',
            'booking' => $booking
        ]);
    }
}

