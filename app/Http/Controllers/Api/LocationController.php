<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\City;

class LocationController extends Controller
{
    public function states()
    {
        return response()->json(State::all());
    }

    public function cities($stateId)
    {
        return response()->json(City::where('state_id', $stateId)->get());
    }
}
