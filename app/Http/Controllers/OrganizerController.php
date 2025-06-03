<?php

namespace App\Http\Controllers;

use App\Models\User; // Assuming organizers are users with a specific role
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    public function index()
    {
        // Fetch users with the 'organizer' role. Ensure you have a 'role' column in your users table.
        $organizers = User::where('role', 'organizer')->get(); 
        return response()->json($organizers);
    }

    public function getEventsByOrganizer(User $organizer)
    {
        // Ensure the user is an organizer
        if ($organizer->role !== 'organizer') {
            return response()->json(['error' => 'User is not an organizer'], 403);
        }
        $events = $organizer->events()->with(['place', 'category'])->get(); // Eager load relationships
        return response()->json($events);
    }
}
