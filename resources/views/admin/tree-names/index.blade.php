@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">🌲 Tree Name Master</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Add Tree Form -->
    <form action="{{ route('tree.names.store') }}" method="POST" class="grid grid-cols-4 gap-2 mb-6">
        @csrf
        <input type="text" name="common_name" class="border p-2 rounded col-span-1" placeholder="Common Name" required>
        <input type="text" name="scientific_name" class="border p-2 rounded col-span-1" placeholder="Scientific Name">
        <input type="text" name="family" class="border p-2 rounded col-span-1" placeholder="Family">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded col-span-1">
            Add
        </button>
    </form>

    <!-- Tree Names List -->
    <ul class="divide-y">
        @foreach($treeNames as $tree)
            <li class="py-2">
                <div class="grid grid-cols-5 gap-2 items-center">
                    <!-- Update Form -->
                    <form action="{{ route('tree.names.update', $tree->id) }}" method="POST" class="col-span-4 grid grid-cols-4 gap-2">
                        @csrf
                        @method('PUT')
                        <input type="text" name="common_name" value="{{ $tree->common_name }}" class="border p-2 rounded">
                        <input type="text" name="scientific_name" value="{{ $tree->scientific_name }}" class="border p-2 rounded">
                        <input type="text" name="family" value="{{ $tree->family }}" class="border p-2 rounded">
                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded">
                            Update
                        </button>
                    </form>

                    <!-- Delete Form -->
                    <form action="{{ route('tree.names.destroy', $tree->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tree?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded">
                            Delete
                        </button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection
