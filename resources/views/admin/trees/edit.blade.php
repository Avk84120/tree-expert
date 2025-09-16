@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800">
        ✏️ Edit Tree
    </h2>
@endsection

@section('content')
    <div class="py-12">
            <!-- Header -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            🌱 Edit Tree
    <div class="p-6">
        <form method="POST" action="{{ route('admin.trees.update', $tree->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm">Tree Name</label>
                <input type="text" name="tree_name" value="{{ old('tree_name', $tree->tree_name) }}" required class="w-full border rounded p-2">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm">Latitude</label>
                    <input type="number" step="0.000001" name="latitude" value="{{ old('latitude', $tree->latitude) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm">Longitude</label>
                    <input type="number" step="0.000001" name="longitude" value="{{ old('longitude', $tree->longitude) }}" class="w-full border rounded p-2">
                </div>
            </div>

            <div>
                <label class="block text-sm">Photo</label>
                <input type="file" name="photo" class="w-full border rounded p-2">
                @if($tree->photo)
                    <p class="mt-2">Current Photo:</p>
                    <img src="{{ asset('storage/' . $tree->photo) }}" alt="{{ $tree->tree_name }}" class="w-48 rounded border mt-2">
                @endif
            </div>

            <div class="flex space-x-3">
                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
                <a href="{{ route('admin.trees.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
