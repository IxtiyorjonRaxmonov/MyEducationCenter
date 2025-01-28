<?php

namespace App\Http\Controllers;

use App\Http\Requests\User_GroupRequest;
use App\Http\Requests\UserGroupAdminRequest;
use App\Http\Requests\UserGroupRequest;
use App\Http\Resources\UserGroupResource;
use App\Models\UserGroup;
use Illuminate\Http\Request;

class UserGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = UserGroup::with([
            'group:id,group_name',
            'user:id,name,surname'

        ])->get();
        
        return response()->json([
            "data" => UserGroupResource::collection($data)
        ], 200);
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
    public function store(UserGroupRequest $request)
    {

        $foundUserGroup = UserGroup::where('user_id', $request->user_id)
            ->where('group_id', $request->group_id)->first();

        if ($foundUserGroup) {
            return response()->json(["message" => "this user already exists in this group"], 200);
        }

        UserGroup::create([
            "user_id" => $request->user_id,
            "group_id" => $request->group_id
        ]);

        return response()->json(["message" => "created"], 200);
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
    public function update(UserGroupAdminRequest $request, string $id)
    {
        $userGroup = UserGroup::find($id);

        if (!$userGroup) {
            return response()->json(["message" => "UserGroup not found"], 404);
        }

        $dataToUpdate = [];
        if ($request->has('user_id')) {
            $dataToUpdate['user_id'] = $request->user_id;
        }

        if ($request->has('group_id')) {
            $dataToUpdate['group_id'] = $request->group_id;
        }

        if ($request->has('active')) {
            $dataToUpdate['active'] = $request->active;
        }

        if (!empty($dataToUpdate)) {
            $userGroup->update($dataToUpdate);
            return response()->json(["message" => "UserGroup updated successfully"], 200);
        } else {
            return response()->json(["message" => "No information provided to update"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usergroup = UserGroup::find($id);
        if (!$usergroup) {
            return response()->json([
                "message" => "usergroup not found!"
            ]);
        } else {
            $usergroup->delete();
            return response()->json([
                "message" => "selected usergroup successfully deleted!"
            ]);
        }
    }
}
