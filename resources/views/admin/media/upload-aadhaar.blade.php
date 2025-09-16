@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4 text-center">🆔 Upload Aadhaar Photo</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('media.upload.aadhaar') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium">User ID (optional)</label>
                <input type="number" name="user_id" class="border rounded p-2 w-full">
            </div>

            <div>
                <label class="block font-medium">Aadhaar Photo</label>
                <input type="file" name="aadhaar_photo" class="border rounded p-2 w-full" required>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded w-full">
                Upload
            </button>
        </form>
    </div>
</div>
@endsection
