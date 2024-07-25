<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    public function index()
    {
        return Space::all();
    }

    public function store(Request $request)
    {
        $space = Space::create($request->all());
        return response()->json($space, 201);
    }

    public function show($id)
    {
        return Space::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $space = Space::findOrFail($id);
        $space->update($request->all());
        return response()->json($space, 200);
    }

    public function destroy($id)
    {
        Space::destroy($id);
        return response()->json(null, 204);
    }
}

