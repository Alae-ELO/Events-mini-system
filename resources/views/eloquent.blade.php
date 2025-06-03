@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-pink-600 text-white">
                    <h2 class="mb-0">Eloquent Query Examples</h2>
                </div>
                <div class="card-body">
                    <!-- Total Booking Quantity -->
                    <div class="mb-5 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">
                            <i class="fas fa-chart-bar text-pink-600 mr-2"></i>
                            Total Booking Quantity
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($bookingcount as $event)
                                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                        <h4 class="font-medium text-gray-700">{{ $event->title }}</h4>
                                        <p class="text-2xl font-bold text-pink-600">{{ $event->total_bookings ?? 0 }}</p>
                                        <p class="text-sm text-gray-500">Total Bookings</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Events with More than 6 Bookings -->
                    <div class="mb-5 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">
                            <i class="fas fa-star text-pink-600 mr-2"></i>
                            Events with More than 6 Bookings
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($moreThan6Booking as $event)
                                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                        <h4 class="font-medium text-gray-700">{{ $event->title }}</h4>
                                        <p class="text-2xl font-bold text-pink-600">{{ $event->total_quantity }}</p>
                                        <p class="text-sm text-gray-500">Total Quantity</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Shared Bookings -->
                    <div class="mb-5 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">
                            <i class="fas fa-users text-pink-600 mr-2"></i>
                            Shared Bookings between Explorers
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($sharedBookings as $booking)
                                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                        <h4 class="font-medium text-gray-700">Event #{{ $booking->event_id }}</h4>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">{{ $booking->explorer1 }}</span>
                                                <span class="text-pink-600 mx-2">and</span>
                                                <span class="font-medium">{{ $booking->explorer2 }}</span>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Organizer Statistics -->
                    <div class="mb-5 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">
                            <i class="fas fa-chart-pie text-pink-600 mr-2"></i>
                            Organizer Statistics
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($organizerStats as $stat)
                                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                        <h4 class="font-medium text-gray-700">Organizer #{{ $stat->organizer_id }}</h4>
                                        <p class="text-2xl font-bold text-pink-600">{{ $stat->total_events }}</p>
                                        <p class="text-sm text-gray-500">Total Events</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 1rem;
    }
    .card-header {
        border-radius: 1rem 1rem 0 0 !important;
    }
    .shadow-lg {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
</style>
@endpush
@endsection 