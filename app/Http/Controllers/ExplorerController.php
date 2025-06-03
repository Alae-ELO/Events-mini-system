<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ExplorerController extends Controller
{
    public function index()
    {
        // Fetch users with the 'explorer' role. Ensure you have a 'role' column in your users table.
        $explorers = User::where('role', 'explorer')->get(); 
        return response()->json($explorers);
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $explorers = User::where('role', 'explorer')
            ->where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->paginate(10);

        return response()->json([
            'explorers' => $explorers->items(),
            'pagination' => [
                'total' => $explorers->total(),
                'per_page' => $explorers->perPage(),
                'current_page' => $explorers->currentPage(),
                'last_page' => $explorers->lastPage(),
                'from' => $explorers->firstItem(),
                'to' => $explorers->lastItem(),
                'links' => $explorers->linkCollection()->toArray()
            ]
        ]);
    }

    public function searchTerm(Request $request, $term) 
    {
        $explorers = User::where('role', 'explorer')
            ->where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->get();

        return response()->json($explorers);
    }
}
