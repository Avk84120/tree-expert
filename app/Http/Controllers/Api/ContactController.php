<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $contact = Contact::create($request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
        ]));
        return response()->json($contact, 201);
    }

    public function index()
    {
        return response()->json(Contact::all());
    }
}
