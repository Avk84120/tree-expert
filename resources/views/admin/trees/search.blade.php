@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800">🔍 Tree Search</h2>
@endsection

@section('content')
<div class="p-6">
    <div class="bg-white shadow rounded-lg p-6">

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.trees.search') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tree Name</label>
                <input type="text" name="tree_name" value="{{ request('tree_name') }}"
                    class="w-full mt-1 border rounded p-2" placeholder="Enter tree name">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Latitude</label>
                <input type="text" name="latitude" value="{{ request('latitude') }}"
                    class="w-full mt-1 border rounded p-2" placeholder="Enter latitude">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Longitude</label>
                <input type="text" name="longitude" value="{{ request('longitude') }}"
                    class="w-full mt-1 border rounded p-2" placeholder="Enter longitude">
            </div>
            <div class="md:col-span-3 text-right">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Search</button>
                <a href="{{ route('admin.trees.search') }}" class="ml-2 bg-gray-600 text-white px-4 py-2 rounded">Reset</a>
            </div>
        </form>

        <!-- Results Table -->
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Tree Name</th>
                    <th class="p-2 text-left">Latitude</th>
                    <th class="p-2 text-left">Longitude</th>
                    <th class="p-2 text-left">Photo</th>
                    <th class="p-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trees as $tree)
                <tr class="border-b">
                    <td class="p-2">{{ $tree->id }}</td>
                    <td class="p-2">{{ $tree->tree_name ?? 'N/A' }}</td>
                    <td class="p-2">{{ $tree->latitude }}</td>
                    <td class="p-2">{{ $tree->longitude }}</td>
                    <td class="p-2">
                        @if($tree->photo)
                            <img src="{{ asset('storage/'.$tree->photo) }}" class="h-12 w-12 object-cover rounded">
                        @else
                            No Photo
                        @endif
                    </td>
                    <td class="p-2">
                        <a href="{{ route('admin.trees.show', $tree) }}" class="bg-blue-600 text-white px-3 py-1 rounded">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">No results found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $trees->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
