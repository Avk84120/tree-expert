@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-green-700 mb-6">✏ Edit Plantation</h1>

        <form method="POST" action="{{ route('plantations.update', $plantation->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ $plantation->name }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" required>
            </div>

            <!-- Area Coordinates -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Area Coordinates</label>
                <textarea name="area_coordinates" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">{{ $plantation->area_coordinates }}</textarea>
            </div>

            <!-- Total Trees -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Total Trees</label>
                <input type="number" name="total_trees" value="{{ $plantation->total_trees }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
            </div>

            <!-- Trees Section -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Select Trees</label>
                <div class="mt-2 space-y-3">
                    @foreach($trees as $tree)
                        @php
                            $pivot = $plantation->trees->where('id', $tree->id)->first();
                        @endphp
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="tree_ids[]" value="{{ $tree->id }}"
                                   {{ $pivot ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">

                            <span class="text-gray-800 flex-1">{{ $tree->common_name }}</span>

                            <input type="number" name="tree_counts[]"
                                   value="{{ $pivot->pivot->count ?? '' }}"
                                   placeholder="Count"
                                   class="w-28 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Existing Photos -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Existing Photos</label>
                <div class="mt-2 flex flex-wrap gap-3">
                    @foreach($plantation->photos as $photo)
                        <img src="{{ asset('storage/'.$photo->path) }}" 
                             class="h-20 w-24 object-cover rounded-md border shadow-sm">
                    @endforeach
                </div>
            </div>

            <!-- Upload New Photos -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Upload More Photos</label>
                <input type="file" name="photos[]" multiple
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0 file:text-sm file:font-semibold
                              file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    💾 Update Plantation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
