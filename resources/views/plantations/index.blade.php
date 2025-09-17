@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">🌱 Plantation List</h1>
        <a href="{{ route('plantations.create') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">+ Add Plantation</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">Total Trees</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">Coordinates</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">Trees</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">Photos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">Actions</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y">
                @foreach($plantations as $plantation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $plantation->name }}</div>
                            <div class="text-xs text-gray-500">Created: {{ optional($plantation->created_at)->format('d M Y') }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $plantation->total_trees ?? $plantation->trees->sum('count') }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700">
                            @php
                                // Try decode coordinates; handle both JSON string and already-decoded array
                                $coords = $plantation->area_coordinates;
                                if (is_string($coords) && $coords !== '') {
                                    $decoded = json_decode($coords, true);
                                    $coords = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
                                }
                            @endphp

                            @if(is_array($coords) && count($coords))
                                <ul class="text-xs text-gray-700 space-y-1">
                                    @foreach($coords as $c)
                                        @php
                                            // Support different key spellings (lat/lng, Lat/Ing, etc.)
                                            $lat = $c['lat'] ?? $c['Lat'] ?? $c['latitude'] ?? ($c[0] ?? null);
                                            $lng = $c['lng'] ?? $c['Lng'] ?? $c['longitude'] ?? $c['Ing'] ?? ($c[1] ?? null);
                                        @endphp
                                        <li>Lat: <span class="font-medium">{{ $lat ?? '-' }}</span> • Lng: <span class="font-medium">{{ $lng ?? '-' }}</span></li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-sm text-gray-400">—</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700">
                            @if($plantation->trees->count())
                                @foreach($plantation->trees as $t)
                                    <div class="mb-1">
                                        <span class="font-medium">{{ $t->tree->common_name ?? $t->tree->name ?? 'N/A' }}</span>
                                        <span class="text-xs text-gray-500"> (x{{ $t->count }})</span>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-sm text-gray-400">No trees</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                @forelse($plantation->photos as $photo)
                                    <img src="{{ asset('storage/' . $photo->path) }}" alt="photo" class="h-12 w-12 object-cover rounded">
                                @empty
                                    <span class="text-sm text-gray-400">No photos</span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('plantations.edit', $plantation) }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>

                                <form action="{{ route('plantations.destroy', $plantation) }}" method="POST" onsubmit="return confirm('Delete this plantation?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-block bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $plantations->links() }}
    </div>
</div>
@endsection
