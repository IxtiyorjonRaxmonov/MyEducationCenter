<?php

namespace App\Http\Traits;

namespace App\Http\Controllers;

use App\Http\Requests\RateRequest;
use App\Http\Requests\UserGroupAdminRequest;
use App\Models\GroupDate;
use App\Models\Rates;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;

use function Laravel\Prompts\select;



class TeacherController extends Controller
{

    public function getTeacherGroups()
    {
        $role = auth()->user()->role->role;
        if ($role == "Teacher") {
            $teacherGroups = UserGroup::where('user_id', auth()->user()->id)
                ->join('groups', 'groups.id', '=', 'user_groups.group_id')
                ->join('subjects', 'subjects.id', '=', 'groups.subject_id')
                ->select('groups.group_name', 'subjects.subject')
                ->get();

            return $teacherGroups;
        } else {
            return response()->json([
                "message" => "you are not Teacher"
            ]);
        }
    }

    public function getTeacherStudents(Request $request)
    {
        $role = auth()->user()->role->role;
        if ($role == "Teacher") {
            $teacherId = auth()->user()->id;
            $requestGroupId = $request->group_id;
            $teacherGroup = UserGroup::where('user_id', $teacherId)
            ->where('group_id', $requestGroupId)
            ->exists();

            if ($teacherGroup) {
                
                $students = UserGroup::where('group_id', $request->group_id)
                ->where('user_id', '!=', auth()->user()->id)
                ->join('users', 'user_groups.user_id', 'users.id')
                ->select('users.name', 'users.surname')
                ->get();
                
                return $students;
            }else {
                return response()->json([
                    "message" => "you are not Teacher of this group"
                ]);
            }
        } else {
            return response()->json([
                "message" => "you are not Teacher"
            ]);
        }
    }

    public function rateForTeacher(RateRequest $request)
    {
        // $teacherId = auth()->user()->id;
        // $role = User::where('users.id', $teacherId)
        //     ->join('roles', 'roles.id', 'users.role_id')
        //     ->pluck('roles.role')->first();

        $role = auth()->user()->role->role;
        if ($role == "Teacher") {
            $active = request('active', true);
            $groupDate = $request->group_date_id;
            $userId = $request->user_id;
            $requestGroupId = $request->group_id;

            $teacherId = auth()->user()->id;
            $teacherGroup = UserGroup::where('user_id', $teacherId)
                ->where('group_id', $requestGroupId)
            ->exists();
                
            if ($teacherGroup) {
                $groupId = GroupDate::where('id', $groupDate)->first();
                // if ($groupId) {
                    $onlyGroupId = $groupId->group_id;

                    if ($onlyGroupId == $requestGroupId) {

                        $userGroup = UserGroup::where('user_id', $userId)
                            ->where('group_id', $onlyGroupId)
                            ->exists();


                        if ($userGroup) {
                            Rates::create([
                                "grade" => $request->grade,
                                "user_id" => $userId,
                                "group_date_id" => $groupDate,
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
                        return response()->json(["message" => "information not found for this group and it's date"], 200);
                    }
                // } else {
                //     return response()->json(["message" => "information not found"], 200);
                // }
            } else {
                return response()->json(["message" => "you are not teacher of this group"], 200);
            }
        } else {
            return response()->json([
                "message" => "you are not Teacher"
            ]);
        }
    }
}
