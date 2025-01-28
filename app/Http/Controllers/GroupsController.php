<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupAdminRequest;
use App\Http\Requests\GroupRequest;
use App\Http\Requests\TestRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\UserGroup;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Group::with([
            'subject:id,subject',
            'level:id,level'
        ])->get();

        return response()->json([
            "data" => GroupResource::collection($data)
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
    public function store(GroupRequest $request)
    {
        $groupName = $request->group_name;
        $requestActive = $request->active;
        $lowerGroupName = $groupName ? strtolower($groupName) : null;
        $active = $requestActive ? '=' : true;
        $GroupNamePlace = Group::where('group_name', $lowerGroupName)->first();
        if (isset($GroupNamePlace)) {
            return response()->json([
                "message" => "group name must be unique"
            ]);
        }
        Group::create([
            "group_name" => $lowerGroupName,
            "subject_id" => $request->subject_id,
            "level_id" => $request->level_id,
            "start_time" => $request->start_time,
            "end_time" => $request->end_time,
            "active" => $active
        ]);

        return response()->json(["message" => "Group created"], 200);
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
    public function update(GroupAdminRequest $request, string $id)
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json(["message" => "Group not found"], 404);
        }

        $dataToUpdate = [];
        if ($request->has('group_name')) {
            $dataToUpdate['group_name'] = $request->group_name;
        }

        if ($request->has('subject_id')) {
            $dataToUpdate['subject_id'] = $request->subject_id;
        }

        if ($request->has('level_id')) {
            $dataToUpdate['level_id'] = $request->level_id;
        }

        if ($request->has('start_time')) {
            $dataToUpdate['start_time'] = $request->start_time;
        }

        if ($request->has('end_time')) {
            $dataToUpdate['end_time'] = $request->end_time;
        }

        if ($request->has('active')) {
            $dataToUpdate['active'] = $request->active;
        }


        if (!empty($dataToUpdate)) {
            $group->update($dataToUpdate);
            return response()->json(["message" => "Group updated successfully"], 200);
        } else {
            return response()->json(["message" => "No information provided to update"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group = Group::find($id);
        if (!$group) {
            return response()->json([
                "message" => "group not found!"
            ]);
        } else {
            $group->delete();
            return response()->json([
                "message" => "selected group successfully deleted!"
            ]);
        }
    }
}
