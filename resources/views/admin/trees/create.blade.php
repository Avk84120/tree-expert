@extends('layouts.app')

<!-- @section('header')
    <h2 class="font-semibold text-xl text-gray-800">Add New Tree</h2>
@endsection -->

@section('content')
 <div class="py-12">
        <!-- Header -->
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight text-center">
          🌱 Add New Tree 
    </h2>
    <div class="p-6">
        <form method="POST" action="{{ route('admin.trees.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm">Tree Name</label>
                <input type="text" name="tree_name" required class="w-full border rounded p-2">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm">Latitude</label>
                    <input type="number" step="0.000001" name="latitude" class="w-full border rounded p-2">
                </div>
                <div>
                    <label class="block text-sm">Longitude</label>
                    <input type="number" step="0.000001" name="longitude" class="w-full border rounded p-2">
                </div>
            </div>

            <div>
                <label class="block text-sm">Photo</label>
                <input type="file" name="photo" class="w-full border rounded p-2">
            </div>

            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Save
            </button>
        </form>
    </div>
@endsection
