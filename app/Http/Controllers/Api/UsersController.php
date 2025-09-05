<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(){
        return response()->json(User::paginate(20));
    }

    public function show($id){
        return response()->json(User::findOrFail($id));
    }

    public function update(Request $r,$id){
        $u = User::findOrFail($id);
        $u->update($r->all());
        return response()->json($u);
    }

    // public function toggleStatus($id){
    //     $u = User::findOrFail($id);
    //     $u->status = !$u->status;
    //     $u->save();
    //     return response()->json(['status'=>$u->status]);
    // }
    public function toggleStatus($id)
{
    $user = User::findOrFail($id);

    // Prevent disabling admin itself
    if ($user->role->name === 'admin') {
        return response()->json(['message' => 'Admin cannot be deactivated'], 403);
    }

    $user->status = !$user->status; // toggle
    $user->save();

    return response()->json([
        'message' => $user->status ? 'User activated successfully' : 'User deactivated successfully',
        'user' => $user
    ]);
}


    public function destroy($id){
        User::findOrFail($id)->delete();
        return response()->json(['message'=>'deleted']);
    }
}
