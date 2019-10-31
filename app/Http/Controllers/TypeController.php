<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;

class TypeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:types'
        ]);

        $type = new Type([
            'name' => $request->input('name'),
        ]);

        $type->save();


        return response()->json($type);
    }

    public function index()
    {
        $types = Type::all();
        return response()->json($types);
    }
    public function destroy(Type $type)
    {
        $type->delete();

        return response()->json([
            'message' => 'Type deleted'
        ]);
    }

}
