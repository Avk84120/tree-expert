<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploadAadhaar(Request $r){
        $r->validate(['aadhaar'=>'required|image|max:5120']);
        $path = $r->file('aadhaar')->store('aadhaar','public');
        $photo = $r->user()->photos()->create(['path'=>$path,'type'=>'aadhaar']);
        return response()->json(['path'=>asset('storage/'.$path)]);
    }
}
