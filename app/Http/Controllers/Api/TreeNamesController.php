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

    public function store(Request $r){
        $data = $r->validate([
            'common_name'=>'required|string',
            'scientific_name'=>'nullable|string',
            'family'=>'nullable|string'
        ]);
        return response()->json(TreeName::create($data),201);
    }

    public function show($id){
        return response()->json(TreeName::findOrFail($id));
    }

    public function update(Request $r,$id){
        $t = TreeName::findOrFail($id);
        $t->update($r->all());
        return response()->json($t);
    }

    public function destroy($id){
        TreeName::findOrFail($id)->delete();
        return response()->json(['message'=>'deleted']);
    }
}
