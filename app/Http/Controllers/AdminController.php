<?php

namespace App\Http\Controllers;

use App\Http\Requests\Group_Subject_LevelRequest;
use App\Http\Requests\GroupRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserGroupAdminRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\SubjectTeacherResource;
use App\Models\Group;
use App\Models\Group_Subject_Level;
use App\Models\Level;
use App\Models\Rates;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Contracts\HasApiTokens;
use PhpParser\Builder\Trait_;


trait FirstUpper
{
    public function firstUpper(UserRequest $request): array
    {
        $name = ucfirst(strtolower($request->name));
        $surname = ucfirst(strtolower($request->surname));

        return [
            'name' => $name,
            'surname' => $surname,
        ];
    }
}


class AdminController extends Controller
{
    use FirstUpper;

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('username', $validated['username'])->first();

        if ($user && Hash::check($validated['password'], $user->password)) {
            $token = $user->createToken("my-education")->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid username or password'], 400);
    }

    public function register(UserRequest $request)
    {

        $role = auth()->user()->role->role;

        if ($role == "Admin") {
            $firstUpper = $this->firstUpper($request);

            $active = request('active', true);

            User::create([
                "name" => $firstUpper['name'],
                "surname" => $firstUpper['surname'],
                "username" => $request->username,
                'password' => bcrypt($request->password),
                "role_id" => $request->role_id,
                "active" => $active,

            ]);
            return response()->json(["message" => "user created"], 200);
        } else {
            return response()->json([
                "message" => "you are not admin"
            ]);
        }
    }

    public function subjectForAdmin(Request $request)
    {
        $role = auth()->user()->role->role;

        if ($role == "Admin") {

            $subject = $request->subject;
            $firstUpperSubject = $subject ? ucfirst(strtolower($subject)) : null;
            $subjectActive = $request->active;

            $subject = Subject::where('subject', isset($firstUpperSubject) ? "=" : "!=", $firstUpperSubject)
                ->where('active', isset($subjectActive) ? "=" : true)
                ->select(
                    'subject'
                )
                ->get();

            if (count($subject) != 0) {
                return response()->json([
                    "message" => $subject
                ]);
            } else {
                return response()->json(["message" => "information not found"], 200);
            }
        } else {
            return response()->json([
                "message" => "you are not admin"
            ]);
        }
    }

    public function roleForAdmin(Request $request)
    {
        $role = auth()->user()->role->role;

        if ($role == "Admin") {

            $role = $request->role;
            $firstUpperRole = $role ? ucfirst(strtolower($role)) : null;
            $roleActive = $request->active;

            $role = Role::where('role', isset($firstUpperRole) ? "=" : "!=", $firstUpperRole)
                ->where('active', isset($roleActive) ? "=" : true)
                ->select(
                    'role'
                )
                ->get();

            if (count($role) != 0) {
                return response()->json([
                    "message" => $role
                ]);
            } else {
                return response()->json(["message" => "information not found"], 200);
            }
        } else {
            return response()->json([
                "message" => "you are not admin"
            ]);
        }
    }

    public function rateForAdmin(Request $request)
    {
        $role = auth()->user()->role->role;

        if ($role == "Admin") {

            $grade = request('grade', null);
            $rateActive = $request->active;
            $user_id = request('user_id', null);
            $group_date_id = $request->group_date_id;

            $rate = Rates::where('grade', isset($grade) ? "=" : "!=", $grade)
                ->where('rates.active', isset($rateActive) ? "=" : true)
                ->where('group_date_id', isset($group_date_id) ? "=" : '!=', $group_date_id)
                ->where('user_id', isset($user_id) ? "=" : '!=', $user_id)
                ->join('users', 'rates.user_id', 'users.id')
                ->join('group_dates', 'rates.group_date_id', 'group_dates.id')
                ->select(
                    'users.name',
                    'group_dates.group_id',
                    'group_dates.date',
                    'grade',
                )
                ->get();


            if (count($rate) != 0) {
                return response()->json([
                    "message" => $rate
                ]);
            } else {
                return response()->json(["message" => "information not found"], 200);
            }
        } else {
            return response()->json([
                "message" => "you are not admin"
            ]);
        }
    }

    public function levelForAdmin(Request $request)
    {
        $role = auth()->user()->role->role;

        if ($role == "Admin") {

            $level = $request->level;
            $firstUpperLevel = $level ? ucfirst(strtolower($level)) : null;
            $price = request('price', null);
            $levelActive = $request->active;

            $level = Level::where('level', isset($firstUpperLevel) ? "=" : "!=", $firstUpperLevel)
                ->where('price', isset($price) ? "=" : "!=", $price)
                ->where('active', isset($levelActive) ? "=" : true)
                ->select(
                    'level',
                    'price'
                )
                ->get();

            if (count($level) != 0) {
                return response()->json([
                    "message" => $level
                ]);
            } else {
                return response()->json(["message" => "information not found"], 200);
            }
        } else {
            return response()->json([
                "message" => "you are not admin"
            ]);
        }
    }


    public function userGroupForAdmin(UserGroupAdminRequest $request)
    {
        $role = auth()->user()->role->role;

        if ($role == "Admin") {

            $userName = $request->username;
            $userNameId = User::where('username', $userName)->pluck('id')->first();


            if (isset($userNameId)) {
                $userGroup = UserGroup::where('user_id', $userNameId)->first();
                if (isset($userGroup)) {
                    return $userGroup;
                } else {
                    return response()->json([
                        "mesage" => "group not found"
                    ]);
                };
            } else {
                return response()->json([
                    "mesage" => "invalid username"
                ]);
            }
        } else {
            return response()->json([
                "message" => "you are not admin"
            ]);
        }
    }

    public function groupForAdmin(Request $request)
    {
        $role = auth()->user()->role->role;

        if ($role == "Admin") {

            $groupName = $request->group_name;
            $lowerGroupName = $groupName ? strtolower($groupName) : null;
            $startTime = request('start_time', null);
            $level_id = request('level_id', null);
            $subject_id = request('subject_id', null);
            $endTime = request('end_time', null);
            $groupActive = request('active', true);

            $group = Group::where('group_name', isset($lowerGroupName) ? "=" : "!=", $lowerGroupName)
                ->where('start_time', isset($startTime) ? "=" : "!=", $startTime)
                ->where('level_id', isset($level_id) ? "=" : "!=", $level_id)
                ->where('subject_id', isset($subject_id) ? "=" : "!=", $subject_id)
                ->where('end_time', isset($endTime) ? "=" : "!=", $endTime)
                ->where('groups.active', $groupActive)
                ->join('subjects', 'groups.subject_id', 'subjects.id')
                ->join('levels', 'groups.level_id', 'levels.id')
                ->select(
                    'group_name',
                    'levels.level',
                    'subjects.subject',
                    'start_time',
                    'end_time'
                )
                ->get();

            if (count($group) != 0) {
                return response()->json([
                    "message" => $group
                ]);
            } else {
                return response()->json(["message" => "information not found"], 200);
            }
        } else {
            return response()->json([
                "message" => "you are not admin"
            ]);
        }
    }

    public function userForAdmin(Request $request)
    {
        $role = auth()->user()->role->role;

        if ($role == "Admin") {

            $name = $request->name;
            $firstUpperName = $name ? ucfirst(strtolower($name)) : null;
            $surname = $request->surname;
            $firstUpperSurname = $surname ? ucfirst(strtolower($surname)) : null;
            $username = $request->username;
            $role_id = $request->role_id;
            $id = request('id', null);
            $userActive = request('active', null);

            $user = User::where('users.id', isset($id) ? "=" : "!=", $id)
                ->where('name', isset($firstUpperName) ? "=" : "!=", $firstUpperName)
                ->where('username', isset($username) ? "=" : "!=", $username)
                ->where('surname', isset($firstUpperSurname) ? "=" : "!=", $firstUpperSurname)
                ->where('role_id', isset($role_id) ? "=" : "!=", $role_id)
                ->where('users.active', isset($userActive) ? "=" : true)
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->select(
                    'users.name',
                    'users.surname',
                    'users.username',
                    'roles.role as mansabi'
                )
                ->get();




            if (count($user) != 0) {
                return response()->json([
                    "message" => $user
                ]);
            } else {
                return response()->json([
                    "message" => "information not found"
                ]);
            }
        } else {
            return response()->json([
                "message" => "you are not admin"
            ]);
        }
    }


    public function subjectTeacherForAdmin(Request $request)
    {
        $role = auth()->user()->role->role;

        if ($role == "Admin") {

            $subject = $request->subject;
            


            $teachers = User::whereHas('groups.subject', function ($query) use ($subject) {
                $query->where('subject', $subject);
            })->whereHas('role', function ($query) {
                $query->where('role', 'Teacher');
            })->select('name', 'surname')
                ->get();


            if (count($teachers) != 0) {
                return response()->json([
                    "subject" => $subject,
                    "teachers" => $teachers
                ], 200);
            } else {
                return response()->json([
                    "message" => "information not found"
                ]);
            }
        } else {
            return response()->json([
                "message" => "you are not admin"
            ]);
        }
    }

    public function theLowestRating(Request $request)
    {
        $role = auth()->user()->role->role;


        if ($role == "Admin") {

            
            // $students = User::with('groups')->get();

            // $studentGrades = [];

            // foreach ($students as $student) {
            //     $totalGrade = 0;

            //     foreach ($student->groups as $group) {
            //         foreach ($group->groupDates as $date) {
            //             foreach ($date->rates as $rate) {
            //                 if ($rate->user_id === $student->id) {
            //                     $totalGrade += $rate->grade;
            //                 }
            //             }
            //         }
            //     }

            //     $studentGrades[] = [
            //         'name' => $student->name,
            //         'surname' => $student->surname,
            //         'id' => $student->id,
            //         'totalGrade' => $totalGrade,
            //     ];
            // }

            // usort($studentGrades, function ($a, $b) {
            //     return $a['totalGrade'] <=> $b['totalGrade'];
            // });

            // $lowestGrades = array_splice($studentGrades, 0, 20);

            // foreach ($lowestGrades as $student) {

            //     return response()->json([
            //         'lowestGrades' => $lowestGrades
            //     ], 200);
            // }
        } else {
            return response()->json([
                "message" => "you are not admin"
            ]);
        }
    }

    public function attendanceStudents(Request $request)
    {
        $role = auth()->user()->role->role;
        $date = request('date', now()->format('Y-m-d'));

        if ($role == "Admin") {
            return Rates::join('group_dates', 'rates.group_date_id', '=', 'group_dates.id')
                        ->join('users', 'rates.user_id', '=', 'users.id')
                        ->where('group_dates.date', $date)
                        ->select('users.name', 'users.surname')
                        ->groupBy('users.id')
                        // ->distinct()
                        ->get();
        
        } else {
            return response()->json([
                "message" => "you are not admin"
            ]);
        }
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
