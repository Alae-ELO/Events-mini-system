@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold">Events by Organizer</h1>
        <div class="flex space-x-4">
            <a href="{{ route('events.app') }}" class="btn btn-outline-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Events
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Select Organizer</h2>
            <select id="organizerSelect" 
                    class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="filterEvents()">
                <option value="">Select an organizer</option>
                @foreach($organizers as $organizer)
                <option value="{{ $organizer->id }}">{{ $organizer->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="eventsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Events will be loaded here via AJAX -->
    </div>
</div>

@push('scripts')
<script>
function filterEvents() {
    const selectedOrganizer = document.getElementById('organizerSelect').value;

    if (!selectedOrganizer) {
        document.getElementById('eventsContainer').innerHTML = '<p class="col-span-full text-center text-gray-500">Please select an organizer</p>';
        return;
    }

    // Show loading state
    document.getElementById('eventsContainer').innerHTML = '<div class="col-span-full text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div></div>';

    // Fetch events for selected organizer
    fetch(`/events-by-organizer/${selectedOrganizer}`)
        .then(response => response.json())
        .then(events => {
            const container = document.getElementById('eventsContainer');
            if (events.length === 0) {
                container.innerHTML = '<p class="col-span-full text-center text-gray-500">No events found for this organizer</p>';
                return;
            }

            container.innerHTML = events.map(event => `
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <h3 class="text-lg font-semibold mb-2">${event.title}</h3>
                    <p class="text-gray-600 mb-2">${event.description}</p>
                    <div class="text-sm text-gray-500">
                        <p>Start: ${new Date(event.start_date).toLocaleDateString()}</p>
                        <p>End: ${new Date(event.end_date).toLocaleDateString()}</p>
                        <p>Place: ${event.place.name}</p>
                        <p>Category: ${event.category.name}</p>
                    </div>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('eventsContainer').innerHTML = 
                '<p class="col-span-full text-center text-red-500">Error loading events. Please try again.</p>';
        });
}

// Initial load
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('eventsContainer').innerHTML = 
        '<p class="col-span-full text-center text-gray-500">Please select an organizer to view events</p>';
});
</script>
@endpush
@endsection 