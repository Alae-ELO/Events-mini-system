@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Events by Organizer</h1>

    <div class="mb-4">
        <label for="organizer" class="block text-gray-700">Select Organizer:</label>
        <select id="organizer" class="w-full px-3 py-2 border rounded">
            <option value="">Select an organizer</option>
            @foreach($organizers as $organizer)
                <option value="{{ $organizer->id }}" {{ $organizer->id == $selectedOrganizer ? 'selected' : '' }}>{{ $organizer->name }}</option>
            @endforeach
        </select>
    </div>

    <div id="events-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Events will be dynamically inserted here -->
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load organizers
        axios.get('/api/organizers')
            .then(response => {
                const organizers = response.data;
                const select = document.getElementById('organizer');
                organizers.forEach(organizer => {
                    const option = document.createElement('option');
                    option.value = organizer;
                    option.textContent = organizer;
                    select.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading organizers:', error));

        // Handle organizer selection
        document.getElementById('organizer').addEventListener('change', function() {
            const organizer = this.value;
            if (organizer) {
                loadEventsByOrganizer(organizer);
            } else {
                document.getElementById('events-container').innerHTML = '';
            }
        });

        function loadEventsByOrganizer(organizer) {
            axios.get(`/api/events/organizer/${organizer}`)
                .then(response => {
                    const events = response.data;
                    const container = document.getElementById('events-container');
                    container.innerHTML = '';

                    events.forEach(event => {
                        container.innerHTML += `
                            <div class="bg-white p-4 rounded-lg shadow">
                                <h2 class="text-xl font-semibold mb-2">${event.title}</h2>
                                <p class="text-gray-600 mb-2">Date: ${event.start_date} - ${event.end_date}</p>
                                <p class="text-gray-600 mb-2">Category: ${event.category}</p>
                                <p class="text-gray-600">Place: ${event.place}</p>
                            </div>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Error loading events:', error);
                    document.getElementById('events-container').innerHTML = 
                        '<p class="text-red-500">Error loading events. Please try again.</p>';
                });
        }
    });
</script>
@endpush
