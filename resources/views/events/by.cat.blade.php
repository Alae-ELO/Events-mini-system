@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Events by Category</h1>

    <div class="mb-4">
        <label for="category" class="block text-gray-700">Select Category:</label>
        <select id="category" class="w-full px-3 py-2 border rounded">
            <option value="">Select a category</option>
        </select>
    </div>

    <div id="table-container">
        <!-- Table will be dynamically inserted here -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load categories
        axios.get('/api/categories')
            .then(response => {
                const categories = response.data;
                const select = document.getElementById('category');
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category;
                    option.textContent = category;
                    select.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading categories:', error));

        // Handle category selection
        document.getElementById('category').addEventListener('change', function() {
            const category = this.value;
            if (category) {
                loadEventsByCategory(category);
            } else {
                document.getElementById('table-container').innerHTML = '';
            }
        });

        function loadEventsByCategory(category) {
            axios.get(`/api/events/category/${category}`)
                .then(response => {
                    const events = response.data;
                    const container = document.getElementById('table-container');
                    
                    let html = `
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Title</th>
                                    <th class="py-2 px-4 border-b">Start Date</th>
                                    <th class="py-2 px-4 border-b">End Date</th>
                                    <th class="py-2 px-4 border-b">Place</th>
                                    <th class="py-2 px-4 border-b">Organizer</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    events.forEach(event => {
                        html += `
                            <tr>
                                <td class="py-2 px-4 border-b">${event.title}</td>
                                <td class="py-2 px-4 border-b">${event.start_date}</td>
                                <td class="py-2 px-4 border-b">${event.end_date}</td>
                                <td class="py-2 px-4 border-b">${event.place}</td>
                                <td class="py-2 px-4 border-b">${event.organizer}</td>
                            </tr>
                        `;
                    });

                    html += '</tbody></table>';
                    container.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading events:', error);
                    document.getElementById('table-container').innerHTML = 
                        '<p class="text-red-500">Error loading events. Please try again.</p>';
                });
        }
    });
</script>
@endpush
