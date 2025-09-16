@extends('layouts.app')

<!-- @section('header')
    <h2 class="font-semibold text-xl text-gray-800">
        
    </h2>
@endsection -->

@section('content')
<div class="py-12">
        <!-- Header -->
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight text-center">
🌳 Tree Details
        </h2>
</div>
    <div class="p-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Tree Information</h3>

            <p><strong>ID:</strong> {{ $tree->id }}</p>
            <p><strong>Name:</strong> {{ $tree->tree_name }}</p>
            <p><strong>Latitude:</strong> {{ $tree->latitude }}</p>
            <p><strong>Longitude:</strong> {{ $tree->longitude }}</p>

            @if($tree->photo)
                <p class="mt-4"><strong>Photo:</strong></p>
                <img src="{{ asset('storage/' . $tree->photo) }}" 
                     alt="{{ $tree->tree_name }}" 
                     class="w-64 rounded border mt-2">
            @endif
        </div>

        <div class="mt-6 flex space-x-3">
            <a href="{{ route('admin.trees.edit', $tree) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded">
                Edit
            </a>
&nbsp;&nbsp;
            <form method="POST" action="{{ route('admin.trees.destroy', $tree) }}">
                @csrf @method('DELETE')
                <button class="bg-red-600 text-white px-4 py-2 rounded"
                        onclick="return confirm('Are you sure you want to delete this tree?')">
                    Delete
                </button>
            </form>

            <a href="{{ route('admin.trees.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded">
                Back
            </a>
        </div>
    </div>
@endsection
