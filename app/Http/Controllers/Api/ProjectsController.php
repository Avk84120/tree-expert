<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index(Request $request){
        $projects = Project::query();
        if ($request->q) $projects->where('name','like','%'.$request->q.'%');
        return response()->json($projects->paginate(20));
    }

    public function store(Request $r){
        $data = $r->validate(['name'=>'required|string']);
        $project = Project::create($data);
        return response()->json($project,201);
    }

    public function show($id){
        return response()->json(Project::findOrFail($id));
    }

    public function update(Request $r,$id){
        $p = Project::findOrFail($id);
        $p->update($r->all());
        return response()->json($p);
    }

    public function destroy($id){
        Project::findOrFail($id)->delete();
        return response()->json(['message'=>'Deleted']);
    }

    public function settings($id){
        $project = Project::findOrFail($id);
        return response()->json($project->settings);
    }

    public function updateSettings(Request $r,$id){
        $p = Project::findOrFail($id);
        $p->settings = $r->all();
        $p->save();
        return response()->json($p->settings);
    }
}
