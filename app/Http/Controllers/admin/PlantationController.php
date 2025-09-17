<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Plantation;
use App\Models\TreeName;
use App\Models\PlantationTree;
use App\Models\PlantationPhoto;
use Illuminate\Http\Request;

class PlantationController extends Controller
{
    // Plantation List
    public function index()
    {
        $plantations = Plantation::with('trees.tree', 'photos')->paginate(10);
        return view('plantations.index', compact('plantations'));
    }

    // Create Form
    public function create()
    {
        $trees = TreeName::all();
        return view('plantations.create', compact('trees'));
    }

    // Store New Plantation
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'area_coordinates' => 'nullable|string',
            'total_trees' => 'nullable|integer',
            'tree_ids' => 'required|array',
            'tree_counts' => 'required|array',
            'photos.*' => 'image|max:2048',
        ]);

        $plantation = Plantation::create($data);

        // Attach Trees
        foreach ($data['tree_ids'] as $key => $tree_id) {
            PlantationTree::create([
                'plantation_id' => $plantation->id,
                'tree_id' => $tree_id,
                'count' => $data['tree_counts'][$key] ?? 1,
            ]);
        }

        // Upload Photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('plantations', 'public');
                PlantationPhoto::create([
                    'plantation_id' => $plantation->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('plantations.index')->with('success', '✅ Plantation created successfully!');
    }

    // Edit Form
public function edit(Plantation $plantation)
    {
        $trees = TreeName::all();
        return view('plantations.edit', compact('plantation', 'trees'));
    }

    public function update(Request $request, Plantation $plantation)
    {
        $plantation->update($request->only(['name', 'location', 'date']));

        // Update trees
        $plantation->trees()->delete();
        if ($request->trees) {
            foreach ($request->trees as $treeId => $count) {
                if ($count > 0) {
                    $plantation->trees()->create([
                        'tree_id' => $treeId,
                        'count' => $count,
                    ]);
                }
            }
        }

        // Update photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('plantations', 'public');
                $plantation->photos()->create(['path' => $path]);
            }
        }

        return redirect()->route('plantations.index')->with('success', '✅ Plantation updated successfully.');
    }
    // Delete
    public function destroy(Plantation $plantation)
    {
        $plantation->delete();
        return redirect()->route('plantations.index')->with('success', '✅ Plantation deleted successfully!');
    }
    

    public function show(Plantation $plantation)
{

    $plantations = Plantation::all();
    return view('plantations.select_plantation', compact('plantations'));
}

    public function selectLand()
{
    // Fetch plantations if needed for selection
    $plantations = Plantation::all();
    return view('plantations.select_plantation', compact('plantations'));
}

public function storeLand(Request $request)
{
    $request->validate([
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    $plantation = Plantation::create([
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'description' => $request->description ?? null,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Plantation land saved successfully',
        'data' => $plantation
    ]);
}
}
