@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Projects</h1>

    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary mb-3">+ Add Project</a>

    <form method="GET" action="{{ route('projects.index') }}" class="mb-3">
        <input type="text" name="search" class="form-control" placeholder="Search project..." value="{{ request('search') }}">
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Client</th>
                <th>Company</th>
                <th>State</th>
                <th>City</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Wards</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $project)
                <tr>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->client }}</td>
                    <td>{{ $project->company }}</td>
                    <td>{{ $project->state->name ?? '-' }}</td>
                    <td>{{ $project->city->name ?? '-' }}</td>
                    <td>{{ $project->start_date }}</td>
                    <td>{{ $project->end_date }}</td>
                    <td>{{ $project->total_wards }}</td>
                    <td>
                        <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Delete this project?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        <a href="{{ route('admin.projects.settings', $project) }}" class="btn btn-sm btn-secondary">Settings</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No projects found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $projects->links() }}
</div>
@endsection
