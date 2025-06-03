<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EloquentController extends Controller
{
    public function index()
    {
        $sharedBookings = \DB::table('bookings as b1')
        ->join('bookings as b2', function($join) {
            $join->on('b1.event_id', '=', 'b2.event_id')
                 ->whereColumn('b1.explorer_id', '<', 'b2.explorer_id');
        })
        ->join('users as u1', 'b1.explorer_id', '=', 'u1.id')
        ->join('users as u2', 'b2.explorer_id', '=', 'u2.id')
        ->select('b1.event_id', 'u1.name as explorer1', 'u2.name as explorer2')
        ->distinct()
        ->get();
        $bookingcount = \App\Models\Event::withCount(['bookings as total_bookings' => function ($query) {
            $query->select(DB::raw("SUM(quantity)"));
        }])->get();
        $moreThan6Booking = \App\Models\Event::withCount(['bookings as total_quantity' => function ($query) {
            $query->select(DB::raw("SUM(quantity)"));
        }])->having('total_quantity', '>', 6)->get();
        $organizerStats = \App\Models\Event::select('organizer_id', \DB::raw('COUNT(*) as total_events'))
        ->groupBy('organizer_id')
        ->orderByDesc('total_events')
        ->get();
        return view('eloquent', compact(
            'sharedBookings',
            'bookingcount',
            'moreThan6Booking',
            'organizerStats'
        ));
    }
} 