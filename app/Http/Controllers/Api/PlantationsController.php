<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plantation;
use Illuminate\Http\Request;

class PlantationsController extends Controller
{
    public function index(){
        return response()->json(Plantation::with('trees','photos')->paginate(20));
    }

    public function store(Request $r){
        $data = $r->validate([
            'project_id'=>'nullable|exists:projects,id',
            'name'=>'required|string',
            'area_coordinates'=>'nullable|array',
            'total_trees'=>'nullable|integer',
        ]);
        $plantation = Plantation::create($data);
        return response()->json($plantation,201);
    }

    public function show($id){
        return response()->json(Plantation::with('trees','photos')->findOrFail($id));
    }

    public function update(Request $r,$id){
        $p = Plantation::findOrFail($id);
        $p->update($r->all());
        return response()->json($p);
    }

    public function destroy($id){
        Plantation::findOrFail($id)->delete();
        return response()->json(['message'=>'deleted']);
    }

    public function addTree(Request $r,$id){
        $p = Plantation::findOrFail($id);
        $r->validate(['tree_id'=>'required|exists:trees,id']);
        $p->trees()->attach($r->tree_id);
        return response()->json(['message'=>'Tree added']);
    }

    public function removeTree($id,$treeId){
        $p = Plantation::findOrFail($id);
        $p->trees()->detach($treeId);
        return response()->json(['message'=>'Tree removed']);
    }

    public function uploadPhotos(Request $r,$id){
        $p = Plantation::findOrFail($id);
        $r->validate(['photos.*'=>'required|image|max:5120']);
        foreach($r->file('photos') as $file){
            $path = $file->store('plantations','public');
            $p->photos()->create(['path'=>$path]);
        }
        return response()->json(['message'=>'Photos uploaded']);
    }
}
