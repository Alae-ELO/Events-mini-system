<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function index()
    {
        $places = Place::all();
        return response()->json($places);
    }

    public function getEventsByPlace(Place $place)
    {
        $events = $place->events()->with(['organizer', 'category'])->get(); // Eager load relationships
        return response()->json($events);
    }
} 