@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold">Bookings</h1>
        <div class="flex space-x-4">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="p-6">
            <form action="{{ route('booking.index') }}" method="GET" class="flex flex-wrap gap-4" id="searchForm">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search" 
                           placeholder="Search bookings..." 
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onkeyup="this.form.submit()">
                </div>
                <div>
                    <select name="status" 
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="hidden">
        <div class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        </div>
    </div>

    <!-- Error State -->
    <div id="errorState" class="hidden">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline" id="errorMessage"></span>
        </div>
    </div>

    <!-- Bookings Grid -->
    <div id="bookingsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($bookings as $booking)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $booking->event->title }}</h3>
                    <p class="text-sm text-gray-600">{{ $booking->event->organizer->name }}</p>
                </div>
                <span class="px-3 py-1 text-sm rounded-full 
                    @if($booking->status == 'confirmed') bg-green-100 text-green-800
                    @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ \Carbon\Carbon::parse($booking->event->start_date)->format('M d, Y H:i') }}
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $booking->event->place->name }}
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ $booking->explorer->name }}
                </div>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <div class="text-sm">
                    <span class="text-gray-600">Quantity:</span>
                    <span class="font-medium">{{ $booking->quantity }}</span>
                </div>
                <div class="text-sm">
                    <span class="text-gray-600">Total:</span>
                    <span class="font-medium">${{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                @if($booking->status == 'pending')
                <button onclick="updateBookingStatus(`{{ $booking->id }}`, 'confirmed')" 
                        class="btn btn-success btn-sm"
                        data-booking-id="{{ $booking->id }}"
                        data-action="confirm">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Confirm
                    </span>
                </button>
                <button onclick="updateBookingStatus({{ $booking->id }}, 'cancelled')" 
                        class="btn btn-danger btn-sm"
                        data-booking-id="{{ $booking->id }}"
                        data-action="cancel">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </span>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Bookings Found</h3>
                <p class="text-gray-600">Try adjusting your search or filter criteria</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
function updateBookingStatus(bookingId, status) {
    if (!confirm('Are you sure you want to ' + status + ' this booking?')) {
        return;
    }

    fetch(`/bookings/${bookingId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Failed to update booking status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the booking status');
    });
}
</script>
@endpush
@endsection
