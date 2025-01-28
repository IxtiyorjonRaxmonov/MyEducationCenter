<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupDateRequest;
use App\Http\Resources\GroupDateResource;
use App\Models\GroupDate;
use Illuminate\Http\Request;

class GroupDateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = GroupDate::with('group')->get();
        return response()->json([
            "data" => GroupDateResource::collection($data)
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
    public function store(GroupDateRequest $request)
    {
        GroupDate::create([
            "group_id" => $request->group_id,
            "date" => $request->date
        ]);
        return response()->json([
            "message" => "successfully created!!"
        ]);
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
    public function update(Request $request, string $id)
    {
        $groupDate = GroupDate::find($id);


        if (!$groupDate) {
            return response()->json(["message" => "GroupDate not found"], 404);
        }

        $dataToUpdate = [];
        if ($request->has('group_id')) {
            $dataToUpdate['group_id'] = ucfirst(strtolower($request->group_id));
        }

        if ($request->has('date')) {
            $dataToUpdate['date'] = $request->date;
        }


        if (!empty($dataToUpdate)) {
            $groupDate->update($dataToUpdate);
            return response()->json(["message" => "GroupDate updated successfully"], 200);
        } else {
            return response()->json(["message" => "No information provided to update"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $groupDate = GroupDate::find($id);
        if (!$groupDate) {
            return response()->json([
                "message" => "Group date not found!"
            ]);
        } else {
            $groupDate->delete();
            return response()->json([
                "message" => "selected Group date successfully deleted!"
            ]);
        }
    }
}
