<?php

namespace App\Http\Controllers;

use App\Http\Requests\levelAdminRequest;
use App\Http\Requests\LevelRequest;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Level::select(['level', 'price'])->get();
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
    public function store(LevelRequest $request)
    {
        $active = request('active', true);
        $level = $request->level;
        $firstUpperLevel = ucfirst(strtolower($level));

        Level::create([
            "level" => $firstUpperLevel,
            "price" => $request->price,
            "active" => $active
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
    public function update(levelAdminRequest $request, string $id)
    {

        $level = Level::find($id);

        if (!$level) {
            return response()->json(["message" => "Level not found"], 404);
        }

        $dataToUpdate = [];
        if ($request->has('level')) {
            $dataToUpdate['level'] = ucfirst(strtolower($request->level));
        }

        if ($request->has('price')) {
            $dataToUpdate['price'] = $request->price;
        }

        if ($request->has('active')) {
            $dataToUpdate['active'] = $request->active;
        }

        if (!empty($dataToUpdate)) {
            $level->update($dataToUpdate); 
            return response()->json(["message" => "Level updated successfully"], 200);
        } else {
            return response()->json(["message" => "No information provided to update"], 400);
        }

        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $level = Level::find($id);
        if (!$level) {
            return response()->json([
                "message" => "level not found!"
            ]);
        } else {
            $level->delete();
            return response()->json([
                "message" => "level successfully deleted!"
            ]);
        }
    }
}
