@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-5xl bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold text-center mb-6">🌳 Tree Photos</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Tree</th>
                        <th class="px-4 py-2 text-left">Photo</th>
                        <th class="px-4 py-2 text-left">Latitude</th>
                        <th class="px-4 py-2 text-left">Longitude</th>
                        <th class="px-4 py-2 text-left">Accuracy</th>
                        <th class="px-4 py-2 text-left">Uploaded At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($photos as $photo)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $photo->id }}</td>
                            <td class="px-4 py-2">
                                {{ optional($photo->tree)->tree_name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2">
                                <img src="{{ asset('storage/'.$photo->path) }}" 
                                     alt="Tree Photo" 
                                     class="h-20 w-20 object-cover rounded">
                            </td>
                            <td class="px-4 py-2">{{ $photo->latitude ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $photo->longitude ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $photo->accuracy ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $photo->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No photos uploaded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $photos->links() }}
        </div>
    </div>
</div>
@endsection
