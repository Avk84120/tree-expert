<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::with(['state', 'city'])
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $states = State::all();
        $cities = City::all();
        return view('admin.projects.create', compact('states', 'cities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'state_id'      => 'required|exists:states,id',
            'city_id'       => 'required|exists:cities,id',
            'client'        => 'required|string|max:255',
            'company'       => 'required|string|max:255',
            'field_officer' => 'required|string|max:255',
            'total_count'   => 'nullable|integer|min:0',
            'ward'          => 'nullable|string|max:255',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'total_wards'   => 'required|integer|min:1',
            'settings'      => 'nullable|array',
        ]);

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $states = State::all();
        $cities = City::all();
        return view('admin.projects.edit', compact('project', 'states', 'cities'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'state_id'      => 'required|exists:states,id',
            'city_id'       => 'required|exists:cities,id',
            'client'        => 'required|string|max:255',
            'company'       => 'required|string|max:255',
            'field_officer' => 'required|string|max:255',
            'total_count'   => 'nullable|integer|min:0',
            'ward'          => 'nullable|string|max:255',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'total_wards'   => 'required|integer|min:1',
            'settings'      => 'nullable|array',
        ]);

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted!');
    }

    // Show form
public function showAssignForm(Project $project)

{
    $users = User::all(); // Get all users (or filter)
    return view('admin.projects.assign-user', compact('project', 'users'));
}

// Handle form submission
public function assignUser(Request $request, Project $project)
{
        echo "hiiiii";die();

    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    // Example: many-to-many relation
    $project->users()->attach($request->user_id);

    return redirect()->route('admin.projects.showAssignForm', $project->id)
                     ->with('success', 'User assigned successfully!');
}




    public function settings(Project $project)
    {
        return view('admin.projects.settings', compact('project'));
    }

    public function updateSettings(Request $request, Project $project)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.ration' => 'nullable|numeric',
            'settings.distance' => 'nullable|numeric',
        ]);

        $project->settings = $request->settings;
        $project->save();

        return back()->with('success', 'Settings updated successfully!');
    }
}
