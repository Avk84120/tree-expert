@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow rounded p-6">
    <h2 class="text-xl font-semibold mb-4">Assign User to Project</h2>

    <!-- Project info -->
    <div class="mb-4">
        <p><strong>Project:</strong> {{ $project->name }}</p>
        <p><strong>Start Date:</strong> {{ $project->start_date }}</p>
    </div>

    <!-- Assign user form -->
    <form action="{{ route('admin.projects.assignUser', $project->id) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="user_id" class="block text-sm font-medium text-gray-700">Select User</label>
            <select name="user_id" id="user_id" class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">-- Choose a user --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
            @error('user_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Assign User
            </button>
        </div>
    </form>
</div>
@endsection
