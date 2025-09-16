@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800">
        📍 Geo-Tag Tree: {{ $tree->tree_name }}
    </h2>
@endsection

@section('content')
 <div class="py-12">
        <!-- Header -->
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight text-center">
        📍 Geo-Tag Tree: {{ $tree->tree_name }}
    </h2>
</div>
    <div class="p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('admin.trees.geotag', $tree) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm">Project</label>
                    <select name="project_id" required class="w-full border rounded p-2">
                        <option value="">-- Select Project --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" 
                                {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm">Latitude</label>
                        <input type="number" step="0.000001" name="latitude" value="{{ old('latitude') }}" required class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block text-sm">Longitude</label>
                        <input type="number" step="0.000001" name="longitude" value="{{ old('longitude') }}" required class="w-full border rounded p-2">
                    </div>
                </div>

                <div>
                    <label class="block text-sm">Photo</label>
                    <input type="file" name="photo" required class="w-full border rounded p-2">
                </div>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                    Save GeoTag
                </button>
                <a href="{{ route('admin.trees.index') }}" class="ml-2 bg-gray-600 text-white px-4 py-2 rounded">
                    Cancel
                </a>
            </form>
        </div>
    </div>
@endsection
