<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Tree;

class MapTreeController extends Controller
{
    public function index()
    {
        // Get tree locations (latitude, longitude, name)
        $trees = Tree::select('id', 'tree_name', 'latitude', 'longitude')->get();

        return view('admin.map-trees.index', compact('trees'));
    }
}
