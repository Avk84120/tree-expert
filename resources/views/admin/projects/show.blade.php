@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>{{ $project->name }}</h1>

    <p><strong>Client:</strong> {{ $project->client }}</p>
    <p><strong>Company:</strong> {{ $project->company }}</p>
    <p><strong>State:</strong> {{ $project->state->name ?? '-' }}</p>
    <p><strong>City:</strong> {{ $project->city->name ?? '-' }}</p>
    <p><strong>Start Date:</strong> {{ $project->start_date }}</p>
    <p><strong>End Date:</strong> {{ $project->end_date }}</p>
    <p><strong>Total Wards:</strong> {{ $project->total_wards }}</p>

    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
