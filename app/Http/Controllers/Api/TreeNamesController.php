<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TreeName;
use Illuminate\Http\Request;

class TreeNamesController extends Controller
{
    public function index(){
        return response()->json(TreeName::paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'common_name' => 'required|string|max:255',
            'scientific_name' => 'nullable|string|max:255',
            'family' => 'nullable|string|max:255',
        ]);

        $tree = TreeName::create($data);
        return response()->json($tree, 201);
    }
    public function show($id){
        return response()->json(TreeName::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $tree = TreeName::findOrFail($id);
        $tree->update($request->all());
        return response()->json($tree);
    }

    public function destroy($id){
        TreeName::findOrFail($id)->delete();
        return response()->json(['message'=>'deleted']);
    }
}
