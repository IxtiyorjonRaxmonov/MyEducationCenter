<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectAdminRequest;
use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Subject::select('subject')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectRequest $request)
    {
        $subject = $request->subject;
        $active = request('active', true);
        $firstUpperSubject = $subject ? ucfirst(strtolower($subject)) : null;
        $subjectPlace = Subject::where('subject', $firstUpperSubject)->first();
        if (isset($subjectPlace)) {
            return response()->json([
                "message" => "subject must be unique"
            ]);
        }
        Subject::create([
            "subject" => $firstUpperSubject,
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
    public function update(SubjectAdminRequest $request, string $id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json(["message" => "Subject not found"], 404);
        }

        $dataToUpdate = [];
        if ($request->has('subject')) {
            $dataToUpdate['subject'] = ucfirst(strtolower($request->subject));
        }

        if ($request->has('active')) {
            $dataToUpdate['active'] = $request->active;
        }

        if (!empty($dataToUpdate)) {
            $subject->update($dataToUpdate);
            return response()->json(["message" => "Subject updated successfully"], 200);
        } else {
            return response()->json(["message" => "No information provided to update"], 400);
        }
    }

    /**
     */
    public function destroy(string $id)
    {
        $subject = Subject::where("id", $id)->delete();
        if (!$subject) {
            return response()->json([
                "message" => "subject not found!"
            ]);
        } else {
            return response()->json([
                "message" => "successfully deleted!"
            ]);
        }
    }
}
