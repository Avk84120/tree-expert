<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TreePhoto;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image; // need intervention/image
use Illuminate\Support\Facades\Storage;

class TreePhotoController extends Controller
{
    public function upload(Request $request)
    {
        $data = $request->validate([
            'tree_id'   => 'required|exists:trees,id',
            'photo'     => 'required|image|mimes:jpg,jpeg,png|max:5120', // 5MB max
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'accuracy'  => 'nullable|string',
        ]);

        // Compress & store
        $image = Image::make($request->file('photo'))
            ->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('jpg', 75); // 75% quality

        $path = 'trees/photos/' . uniqid() . '.jpg';
        Storage::disk('public')->put($path, (string) $image);

        $photo = TreePhoto::create([
            'tree_id'   => $data['tree_id'],
            'path'      => $path,
            'latitude'  => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'accuracy'  => $data['accuracy'] ?? null,
        ]);

        return response()->json([
            'message' => 'Photo uploaded successfully',
            'data'    => [
                'id'       => $photo->id,
                'url'      => asset('storage/' . $photo->path),
                'latitude' => $photo->latitude,
                'longitude'=> $photo->longitude,
                'accuracy' => $photo->accuracy,
            ]
        ], 201);
    }
}
