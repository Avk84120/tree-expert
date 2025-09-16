@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4 text-center">📊 Reports</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="p-6 space-y-4">
            <a href="{{ route('media.download.excel') }}" class="bg-green-600 text-white px-4 py-2 rounded block text-center">⬇ Download Excel</a>
            <a href="{{ route('media.download.kml') }}" class="bg-yellow-600 text-white px-4 py-2 rounded block text-center">⬇ Download KML</a>
        </div>
    </div>
</div>
@endsection
