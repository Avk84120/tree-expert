@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">🌱 Add Plantation</h2>

        <form method="POST" action="{{ route('plantations.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-1 focus:ring-green-500 focus:border-green-500 px-3 py-2"
                       placeholder="Enter plantation name">
            </div>

            <!-- Area Coordinates -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Area Coordinates</label>
                <textarea name="area_coordinates" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2"
                          placeholder="Enter coordinates (JSON or CSV)"></textarea>
            </div>

            <!-- Total Trees -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Total Trees</label>
                <input type="number" name="total_trees"
                       class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm px-3 py-2"
                       placeholder="Total trees">
            </div>

            <!-- Trees List -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Trees</label>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($trees as $tree)
                        <div class="flex items-center gap-3 p-2 border rounded-md">
                            <input id="tree-{{ $tree->id }}" type="checkbox" name="tree_ids[]" value="{{ $tree->id }}"
                                   class="h-4 w-4 text-green-600 rounded">
                            <label for="tree-{{ $tree->id }}" class="flex-1 text-sm text-gray-800">
                                {{ $tree->common_name }}
                            </label>

                            <!-- Use associative counts so backend can read by tree id -->
                            <input type="number" name="tree_counts[{{ $tree->id }}]" min="0" placeholder="Count"
                                   class="w-20 rounded-md border-gray-300 shadow-sm px-2 py-1 text-sm">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Photos -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Photos (min 4)</label>
                <input type="file" name="photos[]" multiple required
                       class="mt-1 block w-full text-sm text-gray-600">
                <p class="text-xs text-gray-500 mt-1">Accepted: jpg, jpeg, png • Max 2MB per file</p>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-green-600 hover:bg-green-700 text-white px-4 py-2 shadow">
                    💾 Save Plantation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
