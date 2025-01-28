<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAdminRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::with('role')->get();
        return response()->json([
            "data" => UserResource::collection($data)
        ]);
        
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
    public function store(UserRequest $request)
    {

        // User::create
        // ([
        //     "name" => $request->name,
        //     "surname" => $request->surname,
        //     "username" => $request->username,
        //     'password' => bcrypt($request->password),
        //     "role_id" => $request->role_id
        // ]);
        // return response()->json(["message" => "created"], 200);

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
    public function update(UserAdminRequest $request, string $id)
    {

        $user = User::find($id);

        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        $dataToUpdate = [];

        if ($request->has('name')) {
            $dataToUpdate['name'] = ucfirst(strtolower($request->name));
        }

        if ($request->has('surname')) {
            $dataToUpdate['surname'] = ucfirst(strtolower($request->surname));
        }

        if ($request->has('username')) {
            $dataToUpdate['username'] = $request->username;
        }

        if ($request->has('password')) {
            $dataToUpdate['password'] = bcrypt($request->password);
        }

        if ($request->has('role_id')) {
            $dataToUpdate['role_id'] = $request->role_id;
        }

        if ($request->has('active')) {
            $dataToUpdate['active'] = $request->active;
        }

        if (!empty($dataToUpdate)) {
            $user->update($dataToUpdate);
            return response()->json(["message" => "User updated successfully"], 200);
        } else {
            return response()->json(["message" => "No information provided to update"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                "message" => "user not found!"
            ]);
        } else {
            $user->delete();
            return response()->json([
                "message" => "user successfully deleted!"
            ]);
        }
    }
}
