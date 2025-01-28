<?php

namespace App\Http\Controllers;

use App\Http\Resources\GetStudentGroupResource;
use App\Http\Resources\StudentRateResource;
use App\Models\GroupDate;
use App\Models\Rates;
use App\Models\Role;
use App\Models\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentsController extends Controller
{
    public function getStudentsGroup()
    {

        $role = auth()->user()->role->role;

        if ($role == "Student") {
            $userId = auth()->user()->id;

            $data = UserGroup::where('user_id', $userId)->with(['group.subject', 'group.level'])->get();

            return response()->json([
                "data" => GetStudentGroupResource::collection($data)
            ]);
            // $userId = auth()->user()->id;
            // return UserGroup::where('user_id', $userId)
            // ->join('groups', 'groups.id', '=', 'user_groups.group_id')
            // ->join('subjects', 'subjects.id', '=', 'groups.subject_id')
            // ->join('levels', 'groups.level_id', '=', 'levels.id')
            // ->select('subjects.subject', 'group_name', 'start_time', 'end_time', 'levels.price')
            // ->get();;
        } else {
            return response()->json([
                "message" => "you are not student"
            ]);
        }
    }

    public function getStudentsGroupDate(Request $request)
    {

        $role = auth()->user()->role->role;
        if ($role == "Student") {

            $groupId = $request->group_id;
            $userId = auth()->user()->id;
            $userGroup = UserGroup::where('user_id', $userId)
                ->where('group_id', $groupId)
                ->exists();
            if ($userGroup) {

                $date = request('date', now()->format('Y-m'));
                $year = substr($date, 0, 4);
                $month = substr($date, 5, 6);

                $startDate = '01';
                $endDate = Carbon::create($year, $month)->daysInMonth;

                $dates = [
                    // oyning birinchi sanasini olish
                    $year . '-' . $month . '-' . $startDate,

                    // oyning oxirgi sanasini olish
                    $year . '-' . $month . '-' . $endDate
                ];

                
                return GroupDate::where('group_id', $groupId)
                ->whereBetween('group_dates.date', $dates)
                ->select('date')
                ->get();
            } else {
                return response()->json([
                    "message" => "you are not in this group"
                ]);
            }
        } else {
            return response()->json([
                "message" => "you are not student"
            ]);
        }
    }

    public function getStudentsRate(Request $request)
    {



        $role = auth()->user()->role->role;

        // $studentId = auth()->user()->id;
        // $role = User::where('users.id', $studentId)
        //     ->join('roles', 'roles.id', 'users.role_id')
        //     ->pluck('roles.role')->first();

        if ($role == "Student") {
            $user = auth()->user();
            
            $student = User::with('groups')->findOrFail($user->id);

            $data = Rates::where('user_id', auth()->user()->id)
                ->with([
                    'user:id,name',
                    'groupDate:id,group_id,date',
                    'groupDate.group:id,group_name,subject_id',
                    'groupDate.group.subject:id,subject'
                ])->get();


            echo "Student: {$student->name} {$student->surname}\n";
            foreach ($student->groups as $group) {
                $totalGrade = 0;
                foreach ($group->groupDates as $date) {
                    foreach ($date->rates as $rate) {
                        if ($rate->user_id === $student->id) {
                            $totalGrade += $rate->grade;
                        }
                    }
                }

                echo "  Group: {$group->group_name}, Student Rating: {$totalGrade}\n";
            }
            return response()->json([
                "data" => StudentRateResource::collection($data)
            ], 200);
        } else {
            return response()->json([
                "message" => "you are not student"
            ]);
        }
    }





    // private function calculate(object $student): array
    // {

    //     foreach ($student->groups as $group) {
    //         $totalGrade = 0;
    //         $groupName = "";
    //         $groupName = $group->group_name;
    //         foreach ($group->groupDates as $date) {
    //             foreach ($date->rates as $rate) {
    //                 if ($rate->user_id === $student->id) {
    //                     $totalGrade += $rate->grade;
    //                 }
    //             }
    //         }

    //         echo "  Group: {$group->group_name}, Student Rating: {$totalGrade}\n";
    //     }
    //     return [
    //         "total_grade" => $totalGrade,
    //         "group_name" => $groupName
    //     ];
    // }
}
