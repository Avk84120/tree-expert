<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    // public function index(Request $request){
    //     $projects = Project::query();
    //     if ($request->q) $projects->where('name','like','%'.$request->q.'%');
    //     return response()->json($projects->paginate(20));
    // }
    public function index(Request $request)
{
    $query = Project::query();

    // 🔍 Search by name
    if ($request->has('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // 🔍 Filter by state
    if ($request->has('state')) {
        $query->where('state', $request->state);
    }

    // 🔍 Filter by client
    if ($request->has('client')) {
        $query->where('client', $request->client);
    }

    $projects = $query->paginate(10);

    return response()->json($projects);
}


//     public function store(Request $r)
// {
//     $data = $r->validate([
//         'name'          => 'required|string|max:255',
//         'state'         => 'required|string|max:255',
//         'client'        => 'required|string|max:255',
//         'company'       => 'required|string|max:255',
//         'field_officer' => 'required|string|max:255',
//         // 'start_date'    => 'required|date',
//         // 'end_date'      => 'required|date|after_or_equal:start_date',
//         // 'total_wards'   => 'required|integer|min:1',
//         // 'settings'      => 'nullable|json',  // store settings as JSON
//     ]);

//     $project = Project::create($data);

//     return response()->json([
//         'message' => 'Project created successfully!',
//         'data'    => $project
//     ], 201);
// }

public function store(Request $r)
{
    $data = $r->validate([
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

    $project = Project::create($data);

    return response()->json([
        'message' => 'Project created successfully!',
        'data'    => $project->load(['state','city'])
    ], 201);
}


    public function show($id){
        return response()->json(Project::findOrFail($id));
    }

    // public function update(Request $r,$id){
    //     $p = Project::findOrFail($id);
    //     $p->update($r->all());
    //     return response()->json($p);
    // }
    public function update(Request $r, $id)
{
    $project = Project::findOrFail($id);

    $data = $r->validate([
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

    return response()->json([
        'message' => 'Project updated successfully!',
        'data'    => $project
    ]);
}

public function assignUser(Request $request, $projectId)
{
    $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);

    $project = Project::findOrFail($projectId);

    // Attach user to project (ignore if already assigned)
    $project->users()->syncWithoutDetaching([$request->user_id]);

    return response()->json([
        'message' => 'User assigned to project successfully',
        'project' => $project->load('users')
    ]);
}



    public function destroy($id){
        Project::findOrFail($id)->delete();
        return response()->json(['message'=>'Deleted']);
    }


    // Get project settings
public function settings($id)
{
    $project = Project::findOrFail($id);

    return response()->json([
        'project_id' => $project->id,
        'settings' => $project->settings ?? []
    ]);
}

// Update project settings
public function updateSettings(Request $request, $id)
{
    $request->validate([
        'settings' => 'required|array',
        'settings.ration' => 'nullable|numeric',
        'settings.distance' => 'nullable|numeric',
    ]);

    $project = Project::findOrFail($id);

    $project->settings = $request->settings;
    $project->save();

    return response()->json([
        'message' => 'Settings updated successfully',
        'settings' => $project->settings
    ]);
}

}
