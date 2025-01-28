<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateAdminRequest;
use App\Http\Requests\RateRequest;
use App\Http\Resources\RateResource;
use App\Models\GroupDate;
use App\Models\Rates;
use App\Models\UserGroup;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;

class RatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Rates::with('user','groupDate.group')->get();
        return response()->json([
            "data" => RateResource::collection($data)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(RateRequest $request)
    {
        $active = request('active', true);
        $groupDateId = $request->group_date_id;
        $user_id = $request->user_id;

        $groupId = GroupDate::where('id', $groupDateId)->first();
        if ($groupId) {
            $onlyGroupId = $groupId->group_id;

            $userGroup = UserGroup::where('user_id', $user_id)
                ->where('group_id', $onlyGroupId)->first();
            if ($userGroup) {
                Rates::create([
                    "grade" => $request->grade,
                    "user_id" => $user_id,
                    "group_date_id" => $groupDateId,
                    "active" => $active
                ]);
                return response()->json([
                    "message" => "created"
                ], 200);
            } else {
                return response()->json([
                    "message" => "this user is not in this group"
                ], 200);
            }
        } else {
            return response()->json(["message" => "information not found"], 200);
        }
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
    public function update(RateAdminRequest $request, string $id)
    {
        $rate = Rates::find($id);

        if (!$rate) {
            return response()->json(["message" => "rate not found"], 404);
        }
        $dataToUpdate = [];

        if ($request->has('grade')) {
            $dataToUpdate['grade'] = ucfirst(strtolower($request->grade));
        }

        if ($request->has('user_id')) {
            $dataToUpdate['user_id'] = ucfirst(strtolower($request->user_id));
        }

        if ($request->has('group_date_id')) {
            $dataToUpdate['group_date_id'] = ucfirst(strtolower($request->group_date_id));
        }



        if ($request->has('active')) {
            $dataToUpdate['active'] = $request->active;
        }


        if (!empty($dataToUpdate)) {
            $rate->update($dataToUpdate);
            return response()->json(["message" => "Rate updated successfully"], 200);
        } else {
            return response()->json(["message" => "No information provided to update"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rate = Rates::where("id", $id)->delete();
        if (!$rate) {
            return response()->json([
                "message" => "rate not found!"
            ]);
        } else {
            return response()->json([
                "message" => "rate successfully deleted!"
            ]);
        }
    }
}
