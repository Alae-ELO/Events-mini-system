<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Place;
use App\Models\User;
use Mpdf\Mpdf;
use App\Http\Requests\EventRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventExport;
use App\Imports\EventImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::with(['category', 'place', 'organizer'])
            ->when(request('search'), function($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(10);

        $categories = Category::all();
        $places = Place::all();
        $organizers = User::where('role', 'organizer')->get();

        if (request()->ajax()) {
            return response()->json([
                'events' => $events->items(),
                'pagination' => [
                    'total' => $events->total(),
                    'per_page' => $events->perPage(),
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage(),
                ]
            ]);
        }
        
        return view('events.app', compact('events', 'categories', 'places', 'organizers'));
    }

    public function create()
    {
        $categories = Category::all();
        $places = \App\Models\Place::all();
        $organizers = User::where('role', 'organizer')->get();
        return view('events.create', compact('categories', 'places', 'organizers'));
    }

    public function store(EventRequest $request)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('picture')) {
                $data['picture'] = $request->file('picture')->store('events', 'public');
            }

            Event::create($data);

            return redirect()->route('events.app')->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create event: ' . $e->getMessage());
        }
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $categories = Category::all();
        $places = Place::all();
        $organizers = User::where('role', 'organizer')->get();
        return view('events.edit', compact('event', 'categories', 'places', 'organizers'));
    }

    public function update(EventRequest $request, Event $event)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('picture')) {
                // Delete old picture if exists
                if ($event->picture) {
                    Storage::disk('public')->delete($event->picture);
                }
                $data['picture'] = $request->file('picture')->store('events', 'public');
            }

            $event->update($data);

            return redirect()->route('events.app')->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update event: ' . $e->getMessage());
        }
    }

    public function destroy(Event $event)
    {
        try {
            if ($event->picture) {
                Storage::disk('public')->delete($event->picture);
            }
            
            $event->delete();

            return redirect()->route('events.app')->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete event: ' . $e->getMessage());
        }
    }

    public function eventsByCategory()
    {
        $categories = Category::all();
        return view('events.by-category', compact('categories'));
    }

    public function getEventsByCategory(Request $request)
    {
        $categoryIds = explode(',', $request->query('categories'));
        $events = Event::whereIn('category_id', $categoryIds)
            ->with(['category', 'place', 'organizer'])
            ->get();
        return response()->json($events);
    }

    public function eventsByOrganizer()
    {
        $organizers = User::where('role', 'organizer')->get();
        return view('events.by-organizer', compact('organizers'));
    }

    public function getEventsByOrganizer(User $organizer)
    {
        if ($organizer->role !== 'organizer') {
            return response()->json(['error' => 'User is not an organizer'], 403);
        }
        $events = $organizer->events()->with(['place', 'category'])->get();
        return response()->json($events);
    }

    public function export()
    {
        return Excel::download(new EventExport, 'events.xlsx');
        
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xls,xlsx,csv'
        ]);
        Excel::import(new EventImport, $request->file('import_file'));
        return redirect()->route('events.app')->with('success', 'Events imported successfully.');
    }
    public function print()
    {
        $events = Event::with(['category', 'place', 'organizer'])->get();
        
        // Create new PDF instance
        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_header' => 10,
            'margin_footer' => 10
        ]);

        // Set document properties
        $mpdf->SetTitle('Events List');
        $mpdf->SetAuthor('Event Management System');
        
        // Generate HTML content
        $html = view('events.print_pdf', compact('events'))->render();
        
        // Write HTML to PDF
        $mpdf->WriteHTML($html);
        
        // Output PDF as download
        return $mpdf->Output('events-list.pdf', 'D');
    }

    public function getChartData()
    {
        // Get events by category
        $categories = Category::withCount('events')->get()
            ->map(function($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->events_count
                ];
            });

        // Get events by month
        $monthly = Event::selectRaw('MONTH(start_date) as month, COUNT(*) as count')
            ->whereYear('start_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'month' => date('F', mktime(0, 0, 0, $item->month, 1)),
                    'count' => $item->count
                ];
            });

        // Get events by organizer
        $organizers = User::where('role', 'organizer')
            ->withCount('events')
            ->get()
            ->map(function($organizer) {
                return [
                    'name' => $organizer->name,
                    'count' => $organizer->events_count
                ];
            });

        return response()->json([
            'categories' => $categories,
            'monthly' => $monthly,
            'organizers' => $organizers
        ]);
    }
}
