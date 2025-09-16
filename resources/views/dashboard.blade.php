@extends('layouts.app')

@section('content')
    <div class="py-12">
        <!-- Header -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight text-center">
                🌱 {{ __('Tree Dashboard') }}
            </h2>
        </div>
    
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Projects -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Projects</h3>
                    <p class="text-4xl font-extrabold text-indigo-600 mt-2">{{ $projects }}</p>
                </div>

                <!-- Trees -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Trees</h3>
                    <p class="text-4xl font-extrabold text-green-600 mt-2">{{ $trees }}</p>
                </div>

                <!-- Plantations -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Plantations</h3>
                    <p class="text-4xl font-extrabold text-yellow-600 mt-2">{{ $plantations }}</p>
                </div>
            </div>

            <!-- Chart (Centered Below Grid) -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 mt-8 max-w-3xl mx-auto">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-6 text-center">
                    📊 Tree Overview
                </h3>
                <canvas id="treeChart" height="140"></canvas>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('treeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Projects', 'Trees', 'Plantations'],
                datasets: [{
                    label: 'Count',
                    data: [{{ $projects ?? 0 }}, {{ $trees ?? 0 }}, {{ $plantations ?? 0 }}],
                    backgroundColor: ['#4f46e5', '#16a34a', '#f59e0b'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
@endpush
