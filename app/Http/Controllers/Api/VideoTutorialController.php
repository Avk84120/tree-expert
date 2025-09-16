<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VideoTutorial;
use Illuminate\Http\Request;

class VideoTutorialController extends Controller
{
    public function index()
    {
        return response()->json(VideoTutorial::all());
    }

    public function store(Request $request)
    {
        $video = VideoTutorial::create($request->validate([
            'title' => 'required|string',
            'url' => 'required|url',
        ]));
        return response()->json($video, 201);
    }
}
