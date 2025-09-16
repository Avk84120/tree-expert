@extends('layouts.app')

<!-- @section('header')
    <h2 class="font-semibold text-xl text-gray-800"> 🌱 Trees</h2>
@endsection -->

@section('content')
 <div class="py-12">
        <!-- Header -->
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight text-center">
          🌱 Tree Management
    </h2>
</div>
    <div class="p-4">
        <a href="{{ route('admin.trees.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">Add Tree</a>
        <a href="{{ route('admin.trees.export') }}" class="bg-blue-600 text-white px-4 py-2 rounded ml-2">Export Excel</a>
        <a href="{{ route('admin.trees.export.kml') }}" class="bg-yellow-600 text-white px-4 py-2 rounded ml-2">Export KML</a>

        <form method="POST" action="{{ route('admin.trees.import') }}" enctype="multipart/form-data" class="mt-4">
            @csrf
            <input type="file" name="file" required>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded">Import</button>
        </form>

        <table class="w-full mt-6 border">
            <thead>
                <tr class="bg-gray-100">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Lat</th>
                    <th>Long</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trees as $tree)
                <tr>
                    <td>{{ $tree->id }}</td>
                    <td>{{ $tree->tree_name }}</td>
                    <td>{{ $tree->latitude }}</td>
                    <td>{{ $tree->longitude }}</td>
                    <td>
                        <a href="{{ route('admin.trees.show',$tree) }}" class="text-blue-600">View</a> |
                        <a href="{{ route('admin.trees.edit',$tree) }}" class="text-green-600">Edit</a> |
                        <form method="POST" action="{{ route('admin.trees.destroy',$tree) }}" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $trees->links() }}
    </div>
@endsection