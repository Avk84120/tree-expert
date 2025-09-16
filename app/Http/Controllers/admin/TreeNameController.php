<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TreeName;

class TreeNameController extends Controller
{
    // Show list of tree names
    public function index()
    {
        $treeNames = TreeName::orderBy('id', 'desc')->get();
        return view('admin.tree-names.index', compact('treeNames'));
    }

    // Add a new tree name
    public function store(Request $request)
    {
        $request->validate([
            'common_name' => 'required|string|unique:tree_names,common_name',
            'scientific_name' => 'nullable|string',
            'family' => 'nullable|string',
        ]);

        TreeName::create([
            'common_name'     => $request->common_name,
            'scientific_name' => $request->scientific_name,
            'family'          => $request->family,
        ]);

        return redirect()->route('tree.names.index')->with('success', '✅ Tree added successfully!');
    }

    // Update tree name
    public function update(Request $request, $id)
    {
        $treeName = TreeName::findOrFail($id);

        $request->validate([
            'common_name' => 'required|string|unique:tree_names,common_name,' . $treeName->id,
            'scientific_name' => 'nullable|string',
            'family' => 'nullable|string',
        ]);

        $treeName->update([
            'common_name'     => $request->common_name,
            'scientific_name' => $request->scientific_name,
            'family'          => $request->family,
        ]);

        return redirect()->route('tree.names.index')->with('success', '✅ Tree updated successfully!');
    }

    public function destroy($id)
{
    $tree = TreeName::findOrFail($id);
    $tree->delete();

    return redirect()->route('tree.names.index')->with('success', '✅ Tree deleted successfully!');
}
}
