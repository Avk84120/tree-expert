@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Create Project</h1>

    <form method="POST" action="{{ route('admin.projects.store') }}">
        @csrf
        @include('admin.projects.partials.form', ['buttonText' => 'Save Project'])
    </form>
</div>
@endsection
