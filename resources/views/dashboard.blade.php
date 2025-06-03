@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold">{{ __('dashboard.title') }}</h1>
            <p class="text-gray-600">{{ __('dashboard.welcome') }}</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard', ['locale' => 'en']) }}" class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-primary' }}">EN</a>
                <a href="{{ route('dashboard', ['locale' => 'fr']) }}" class="btn btn-sm {{ app()->getLocale() == 'fr' ? 'btn-primary' : 'btn-outline-primary' }}">FR</a>
                <a href="{{ route('dashboard', ['locale' => 'ar']) }}" class="btn btn-sm {{ app()->getLocale() == 'ar' ? 'btn-primary' : 'btn-outline-primary' }}">AR</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('events.app') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
            <h2 class="text-xl font-semibold mb-2">Events</h2>
            <p class="text-gray-600">Manage your events</p>
        </a>
        <a href="{{ route('booking.index') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
            <h2 class="text-xl font-semibold mb-2">Bookings</h2>
            <p class="text-gray-600">View and manage bookings</p>
        </a>
        <a href="{{ route('organizers') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
            <h2 class="text-xl font-semibold mb-2">Organizers</h2>
            <p class="text-gray-600">Manage event organizers</p>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Events by Category</h2>
            <canvas id="categoryChart"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Events by Month</h2>
            <canvas id="monthlyChart"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Events by Organizer</h2>
            <canvas id="organizerChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize charts with empty data
    const categoryChart = new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
            }]
        }
    });

    const monthlyChart = new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Events',
                data: [],
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    const organizerChart = new Chart(document.getElementById('organizerChart'), {
        type: 'pie',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40',
                    '#8AC24A',
                    '#9C27B0',
                    '#E91E63',
                    '#2196F3'
                ]
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 15,
                        padding: 15
                    }
                }
            }
        }
    });

    // Fetch and update chart data
    fetch('/api/dashboard/charts')
        .then(response => response.json())
        .then(data => {
            // Update category chart
            categoryChart.data.labels = data.categories.map(c => c.name);
            categoryChart.data.datasets[0].data = data.categories.map(c => c.count);
            categoryChart.update();

            // Update monthly chart
            monthlyChart.data.labels = data.monthly.map(m => m.month);
            monthlyChart.data.datasets[0].data = data.monthly.map(m => m.count);
            monthlyChart.update();

            // Update organizer chart
            organizerChart.data.labels = data.organizers.map(o => o.name);
            organizerChart.data.datasets[0].data = data.organizers.map(o => o.count);
            organizerChart.update();
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
        });
</script>
@endpush
@endsection
