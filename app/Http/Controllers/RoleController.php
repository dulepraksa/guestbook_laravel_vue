<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;

class RoleController extends Controller
{
    public function index() {
        $roles = Role::all();
        return response()->json($roles);
    }
    public function update() {
        $role = Role::findOrFail(1);

        $role->update(['name' => 'Staff']);

        return $role;
    }
}
