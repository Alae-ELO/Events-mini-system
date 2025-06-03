<?php

namespace App\Http\Controllers;
use App\Models\Event;
use \App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
    public function events()
    {
        $events = Event::with(['category', 'place', 'organizer'])->get();
        $formatted = $events->map(function($event) {
            return [
                'Title' => $event->title,
                'Start ON' => $event->start_date ? (\Carbon\Carbon::parse($event->start_date)->format('Y-m-d')) : '',
                'End ON' => $event->end_date ? (\Carbon\Carbon::parse($event->end_date)->format('Y-m-d')) : '',
                'Category' => $event->category ? $event->category->name : '',
                'Place' => $event->place ? $event->place->name : '',
                'Organizer' => $event->organizer ? $event->organizer->name : '',
            ];
        });
        return response()->json($formatted);
    }
    public function booking()
    {
        $bookings = Booking::with(['event', 'explorer'])->get();
        return view('booking.index', compact('bookings'));
    }
    public function places()
    {
        $places = \App\Models\Place::all();
        $formatted = $places->map(function($place) {
            return [
                'Name' => $place->name,
                'Address' => $place->address,
                'City' => $place->city,
                'Country' => $place->country,
            ];
        });
        return view('places.app', ['data' => $formatted]);
    }
    public function organizers()
    {
        $organizers = User::where('role', 'organizer')->get();
        $formatted = $organizers->map(function($organizer) {
            return [
                'Name' => $organizer->name,
                'Email' => $organizer->email,
                'Phone' => $organizer->phone,
            ];
        }); 
        return view('organizers.app', ['data' => $formatted]);
    }
    public function bookings(){

        return view("booking.app");
    }
    public function section(){
        return view("section");
    }
    public function saveCookie()
   {
      $name = request()->input("txtCookie");
      //Cookie::put("UserName",$name,6000000);
      Cookie::queue("OrganizerName",$name,6000000);
      return redirect()->back();
   }
   public function saveSession()
   {
      $name = request()->input("txtSession");
      Session::put("SessionName",$name);
      return redirect()->back();
   }
}