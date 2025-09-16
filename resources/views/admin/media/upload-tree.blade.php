@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold text-center mb-6">📸 Upload Tree Photo</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('media.upload.tree') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Tree ID (optional)</label>
                <input type="number" name="tree_id" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Photo</label>
                <input type="file" name="tree_photo" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Latitude</label>
                <input type="text" name="latitude" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Longitude</label>
                <input type="text" name="longitude" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Accuracy</label>
                <input type="text" name="accuracy" class="w-full border rounded px-3 py-2">
            </div>

            <div class="text-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
