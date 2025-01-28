<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleAdminRequest;
use App\Http\Requests\RoleRequest;
use App\Models\Rates;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Role::select('role')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {

        $role = $request->role;
        $active = request('active', true);
        $firstUpperRole = $role ? ucfirst(strtolower($role)) : null;
        $rolePlace = Role::where('role', $firstUpperRole)->first();
        if (isset($rolePlace)) {
            return response()->json([
                "message" => "role must be unique"
            ]);
        }
        Role::create([
            "role" => $firstUpperRole,
            "active" => $request->active
        ]);

        return response()->json(["message" => "role created"], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleAdminRequest $request, string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(["message" => "Role not found"], 404);
        }

        $dataToUpdate = [];
        if ($request->has('role')) {
            $dataToUpdate['role'] = ucfirst(strtolower($request->role));
        }

        if ($request->has('active')) {
            $dataToUpdate['active'] = $request->active;
        }


        if (!empty($dataToUpdate)) {
            $role->update($dataToUpdate);
            return response()->json(["message" => "Role updated successfully"], 200);
        } else {
            return response()->json(["message" => "No information provided to update"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $role = Role::where("id", $id)->delete();
        if (!$role) {
            return response()->json([
                "message" => "role not found!"
            ]);
        } else {
            return response()->json([
                "message" => "role successfully deleted!"
            ]);
        }
    }
}
