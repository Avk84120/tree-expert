@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Project</h1>

    <form method="POST" action="{{ route('admin.projects.update', $project) }}">
        @csrf
        @method('PUT')
        @include('admin.projects.partials.form', ['buttonText' => 'Update Project'])
    </form>
</div>
@endsection
