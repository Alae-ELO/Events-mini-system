@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold">Events by Category</h1>
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
            <h2 class="text-lg font-semibold mb-4">Filter by Categories</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="categoryFilters">
                @foreach($categories as $category)
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="category_{{ $category->id }}" 
                           value="{{ $category->id }}" 
                           class="category-checkbox w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                           onchange="filterEvents()">
                    <label for="category_{{ $category->id }}" class="ml-2 text-sm text-gray-700">
                        {{ $category->name }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="eventsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Events will be loaded here via AJAX -->
    </div>
</div>

@push('scripts')
<script>
function filterEvents() {
    const selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedCategories.length === 0) {
        document.getElementById('eventsContainer').innerHTML = '<p class="col-span-full text-center text-gray-500">Please select at least one category</p>';
        return;
    }

    // Show loading state
    document.getElementById('eventsContainer').innerHTML = '<div class="col-span-full text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div></div>';

    // Fetch events for selected categories
    fetch(`/events-by-category/filter?categories=${selectedCategories.join(',')}`)
        .then(response => response.json())
        .then(events => {
            const container = document.getElementById('eventsContainer');
            if (events.length === 0) {
                container.innerHTML = '<p class="col-span-full text-center text-gray-500">No events found for selected categories</p>';
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
                        <p>Organizer: ${event.organizer.name}</p>
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
        '<p class="col-span-full text-center text-gray-500">Please select categories to view events</p>';
});
</script>
@endpush
@endsection 