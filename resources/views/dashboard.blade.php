@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="display-4 mb-4 text-center">Welcome to Event Management System</h2>
    
    <div class="d-flex justify-content-center gap-3 mb-5">
        <a href="/events" class="btn bg-blue-500 btn-lg shadow-sm rounded-lg px-4 py-2 hover:bg-blue-900 text-white transition-all duration-300">List of Events</a>
        <a href="/booking" class="btn bg-purple-500 btn-lg shadow-sm rounded-lg px-4 py-2 hover:bg-purple-900 text-white transition-all duration-300">List of Bookings</a>
        <a href="/organizers" class="btn bg-green-500 btn-lg shadow-sm rounded-lg px-4 py-2 hover:bg-green-900 text-white transition-all duration-300">List of Organizers</a>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Events by Category</h5>
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Events by Month</h5>
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Events by Organizer</h5>
                    <canvas id="organizerChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let categoryChart, monthlyChart, organizerChart;

    // Initialize charts with empty data
    function initializeCharts() {
        // Category Chart
        categoryChart = new Chart(document.getElementById('categoryChart'), {
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
                        '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Monthly Chart
        monthlyChart = new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Number of Events',
                    data: [],
                    backgroundColor: '#36A2EB'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Organizer Chart
        organizerChart = new Chart(document.getElementById('organizerChart'), {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Number of Events',
                    data: [],
                    backgroundColor: '#4BC0C0'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Update charts with real data
    function updateCharts(data) {
        // Update Category Chart
        categoryChart.data.labels = data.categories.map(item => item.name);
        categoryChart.data.datasets[0].data = data.categories.map(item => item.count);
        categoryChart.update();

        // Update Monthly Chart
        monthlyChart.data.labels = data.monthly.map(item => item.month);
        monthlyChart.data.datasets[0].data = data.monthly.map(item => item.count);
        monthlyChart.update();

        // Update Organizer Chart
        organizerChart.data.labels = data.organizers.map(item => item.name);
        organizerChart.data.datasets[0].data = data.organizers.map(item => item.count);
        organizerChart.update();
    }

    // Initialize charts
    initializeCharts();

    // Fetch real data from the API
    fetch('/api/dashboard/charts')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            updateCharts(data);
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
            // Show error message to user
            const errorMessage = document.createElement('div');
            errorMessage.className = 'alert alert-danger';
            errorMessage.textContent = 'Failed to load chart data. Please try again later.';
            document.querySelector('.container').prepend(errorMessage);
        });
});
</script>
@endpush
@endsection
