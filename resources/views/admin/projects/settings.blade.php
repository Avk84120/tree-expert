@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Project Settings - {{ $project->name }}</h1>

    <form method="POST" action="{{ route('admin.projects.updateSettings', $project) }}">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="settings[ration]">Ration</label>
            <input type="number" step="0.01" name="settings[ration]" class="form-control"
                value="{{ $project->settings['ration'] ?? '' }}">
        </div>

        <div class="form-group mb-3">
            <label for="settings[distance]">Distance</label>
            <input type="number" step="0.01" name="settings[distance]" class="form-control"
                value="{{ $project->settings['distance'] ?? '' }}">
        </div>

        <button type="submit" class="btn btn-primary">Update Settings</button>
    </form>
</div>
@endsection
