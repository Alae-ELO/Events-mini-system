@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold">Events Management</h1>
        <div class="flex space-x-4">
            <a href="{{ route('event.create') }}" class="btn btn-primary">

                Create Event
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                Back to Dashboard
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="p-6">
            <div class="flex flex-wrap gap-4 justify-between items-center">
                <div class="flex gap-4">
                    <form action="{{ route('events.import') }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
                        @csrf
                        <input type="file" name="import_file" class="form-control" accept=".xls,.xlsx,.csv" required>
                        <button type="submit" class="btn btn-outline-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Import
                        </button>
                    </form>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('events.export') }}" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </a>
                    <a href="{{ route('events.print') }}" target="_blank" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print the list
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class='bg-white flex gap-4 justify-center items-center mt-8'>

     <a href="{{ route('events.by.cat') }}" class="bg-pink-500  text-white px-4 py-2 rounded-md hover:bg-pink-600 transition-colors">

                    Filter by Category
                </a>
                <a href="{{ route('events.by.org') }}" class="bg-pink-500  text-white px-4 py-2 rounded-md hover:bg-pink-600 transition-colors">

                    Filter by Organizer
                </a>
</div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Place</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organizer</th>
                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($events as $event)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $event->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $event->start_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $event->end_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $event->category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $event->place->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $event->organizer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-outline-primary btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('Are you sure you want to delete this event?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection