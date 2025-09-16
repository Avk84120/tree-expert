<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function show()
    {
        return response()->json(PrivacyPolicy::latest()->first());
    }

    public function update(Request $request)
    {
        $policy = PrivacyPolicy::create([
            'content' => $request->input('content')
        ]);
        return response()->json($policy);
    }
}
