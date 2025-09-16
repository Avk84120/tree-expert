<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return response()->json(Faq::all());
    }

    public function store(Request $request)
    {
        $faq = Faq::create($request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]));
        return response()->json($faq, 201);
    }
}
