<div class="form-group mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $project->name ?? '') }}">
    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="form-group mb-3">
    <label>State</label>
    <select name="state_id" class="form-control">
        <option value="">Select State</option>
        @foreach($states as $state)
            <option value="{{ $state->id }}" {{ old('state_id', $project->state_id ?? '') == $state->id ? 'selected' : '' }}>
                {{ $state->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label>City</label>
    <select name="city_id" class="form-control">
        <option value="">Select City</option>
        @foreach($cities as $city)
            <option value="{{ $city->id }}" {{ old('city_id', $project->city_id ?? '') == $city->id ? 'selected' : '' }}>
                {{ $city->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label>Client</label>
    <input type="text" name="client" class="form-control" value="{{ old('client', $project->client ?? '') }}">
</div>

<div class="form-group mb-3">
    <label>Company</label>
    <input type="text" name="company" class="form-control" value="{{ old('company', $project->company ?? '') }}">
</div>

<div class="form-group mb-3">
    <label>Field Officer</label>
    <input type="text" name="field_officer" class="form-control" value="{{ old('field_officer', $project->field_officer ?? '') }}">
</div>

<div class="form-group mb-3">
    <label>Total Count</label>
    <input type="number" name="total_count" class="form-control" value="{{ old('total_count', $project->total_count ?? '') }}">
</div>

<div class="form-group mb-3">
    <label>Ward</label>
    <input type="text" name="ward" class="form-control" value="{{ old('ward', $project->ward ?? '') }}">
</div>

<div class="form-group mb-3">
    <label>Start Date</label>
    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $project->start_date ?? '') }}">
</div>

<div class="form-group mb-3">
    <label>End Date</label>
    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $project->end_date ?? '') }}">
</div>

<div class="form-group mb-3">
    <label>Total Wards</label>
    <input type="number" name="total_wards" class="form-control" value="{{ old('total_wards', $project->total_wards ?? '') }}">
</div>

<button type="submit" class="btn btn-success">{{ $buttonText }}</button>
<a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
