<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Projects -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold">Projects</h3>
                    <p class="text-4xl font-extrabold text-green-600 mt-2">
                        {{ $stats['projects'] }}
                    </p>
                </div>

                <!-- Trees -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold">Trees</h3>
                    <p class="text-4xl font-extrabold text-green-600 mt-2">
                        {{ $stats['trees'] }}
                    </p>
                </div>

                <!-- Plantations -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold">Plantations</h3>
                    <p class="text-4xl font-extrabold text-green-600 mt-2">
                        {{ $stats['plantations'] }}
                    </p>
                </div>

            </div>

            <!-- Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('dashboardChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Projects', 'Trees', 'Plantations'],
                datasets: [{
                    label: 'Statistics',
                    data: [
                        {{ $stats['projects'] }},
                        {{ $stats['trees'] }},
                        {{ $stats['plantations'] }}
                    ],
                    backgroundColor: ['#4ade80', '#60a5fa', '#facc15'],
                }]
            }
        });
    </script>
</x-app-layout>
