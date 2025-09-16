<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Tree;
use App\Models\TreeName;
use App\Models\TreePhoto;
use App\Models\Plantation;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class TreeWebController extends Controller
{
    public function search(Request $request)
{
    $query = Tree::query();

    // Apply filters if provided
    if ($request->filled('tree_name')) {
        $query->where('tree_name', 'like', '%' . $request->tree_name . '%');
    }
    if ($request->filled('latitude')) {
        $query->where('latitude', $request->latitude);
    }
    if ($request->filled('longitude')) {
        $query->where('longitude', $request->longitude);
    }

    $trees = $query->latest()->paginate(10);

    return view('admin.trees.search', compact('trees'));
}

    public function index(Request $request)
    {
        $trees = Tree::latest()->paginate(10);
        return view('admin.trees.index', compact('trees'));
    }

    public function create()
    {
        return view('admin.trees.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // 'project_id' => 'required|exists:projects,id',
            'tree_name'  => 'required|string',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
            'photo'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('trees', 'public');
        }

        Tree::create($data);

        return redirect()->route('admin.trees.index')->with('success', 'Tree added successfully!');
    }

    public function show(Tree $tree)
    {
        return view('admin.trees.show', compact('tree'));
    }

    public function edit(Tree $tree)
    {
        return view('admin.trees.edit', compact('tree'));
    }

    public function update(Request $request, Tree $tree)
    {
        $data = $request->validate([
            'tree_name' => 'required|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('trees', 'public');
        }

        $tree->update($data);

        return redirect()->route('admin.trees.index')->with('success', 'Tree updated successfully!');
    }

    public function destroy(Tree $tree)
    {
        $tree->delete();
        return redirect()->route('admin.trees.index')->with('success', 'Tree deleted!');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);
        $collection = (new FastExcel)->import($request->file('file'));

        foreach ($collection as $row) {
            Tree::create([
                'tree_name' => $row['tree_name'] ?? 'Unnamed Tree',
                'latitude'  => $row['latitude'] ?? null,
                'longitude' => $row['longitude'] ?? null,
            ]);
        }

        return redirect()->route('trees.index')->with('success', 'Trees imported!');
    }

    public function exportExcel()
    {
        $fileName = 'trees_export.xlsx';
        (new FastExcel(Tree::all()))->export($fileName);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }

    public function exportKml()
    {
        $trees = Tree::whereNotNull('latitude')->get();
        $kml = '<?xml version="1.0" encoding="UTF-8"?>
                <kml xmlns="http://www.opengis.net/kml/2.2"><Document>';
        foreach ($trees as $t) {
            $kml .= "<Placemark><name>{$t->tree_name}</name>";
            $kml .= "<Point><coordinates>{$t->longitude},{$t->latitude},0</coordinates></Point></Placemark>";
        }
        $kml .= '</Document></kml>';
        return response($kml, 200, ['Content-Type' => 'application/vnd.google-earth.kml+xml']);
    }

    public function geotagForm(Tree $tree)
{
    $projects = Project::all(); // don’t forget: use App\Models\Project;
    return view('admin.trees.geotag', compact('tree', 'projects'));
}

// Save the GeoTag
public function geotag(Request $request, Tree $tree)
{
    $data = $request->validate([
        'project_id' => 'required|exists:projects,id',
        'latitude'   => 'required|numeric',
        'longitude'  => 'required|numeric',
        'photo'      => 'required|image|max:4096',
    ]);

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('trees', 'public');
    }

    // Attach geotag info to this tree
    $tree->update($data);

    return redirect()->route('admin.trees.index')
                     ->with('success', 'Tree geotagged!');
}
}
