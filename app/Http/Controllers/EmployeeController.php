<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\EmployeeSkills;
use App\Models\Skills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use StdClass;

class EmployeeController extends Controller
{

    public function employee_list()
    {

        $user = Auth::guard('emp')->user();
        $employees = Employee::select('employees.token', 'employees.name', 'employees.email', 'employees.contact_no', 'employees.image', 'departments.name as department_name')
            ->join('departments', 'employees.department', '=', 'departments.token')
            ->where('user_token', $user->token)
            ->get();

        $employee_count = count($employees);

        $employeeTokens = [];
        foreach ($employees as $employee) {
            $employeeTokens[] = $employee->token;
        }

        $emp_skills = Skills::select('skills.name', 'employee_skills.employee_token')
            ->join('employee_skills', 'skills.token', '=', 'employee_skills.skills_token')
            ->whereIn('employee_skills.employee_token', $employeeTokens)
            ->get();

        $arr = [];

        foreach ($employees as $employee) {
            $obj = new StdClass;
            $obj->name = $employee->name;
            $obj->email = $employee->email;
            $obj->contact_no = $employee->contact_no;
            $obj->department_name = $employee->department_name;
            $obj->image = $employee->image;
            $obj->skills = [];

            $arr[$employee->token] = $obj;
        }

        foreach ($emp_skills as $skills) {
            $employeeToken = $skills->employee_token;
            $arr[$employeeToken]->skills[] = $skills->name;
        }

        return response()->json([
            'user_image' => $user->image,
            'user_name' => $user->name,
            'employee_count' => $employee_count,
            'employee_list' => $arr
        ]);
    }


    public function create_employee(Request $request)
    {
        //
        $user = Auth::guard('emp')->user();

        $validate = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3',
            'email' => 'required|email|unique:employees',
            'department' => 'required|string',
            'contact_no' => 'required|string|min:10|max:10',
            'dob' => 'required|date',
            'blood_group' => 'required|string',
            'address' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'Error' => $validate->messages()
            ]);
        } else {
            $image = "";
            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('post', 'public');
            } else {
                $image = "null";
            }


            $employee = Employee::create([
                'token' => rand(100000, 999999),
                'name' => $request->name,
                'email' => $request->email,
                'department' => $request->department,
                'contact_no' => $request->contact_no,
                'dob' => $request->dob,
                'blood_group' => $request->blood_group,
                'address' => $request->address,
                'image' => $image,
                'user_token' => $user->token,
            ]);


            $employee_skills = [];
            $employee_token = $employee->value('token');
            foreach ($request['skill_token'] as $skill_token) {
                $employee_skill = EmployeeSkills::create([
                    'employee_token' => $employee_token,
                    'skills_token' => $skill_token,
                    'token' => rand(100000, 999999)
                ]);

                $employee_skills[] = $employee_skill;
            }

            if ($employee) {
                return response()->json([
                    'Message' => 'One Record created successfully',
                    'Emaployee' => $employee
                ]);
            }
        }
    }


    public function filter_employee(Request $request)
    {
        //
        $user = Auth::guard('emp')->user();

        $employee_skill_query = EmployeeSkills::select('employee_skills.employee_token')
            ->join('skills', 'employee_skills.skills_token', '=', 'skills.token')
            ->whereIn('skills.name', $request->skill)
            ->groupBy('employee_skills.employee_token')
            ->get();

        $employee_skill_token = [];
        foreach ($employee_skill_query as $employee_skill_query) {
            $employee_skill_token[] = $employee_skill_query->employee_token;
        }
        // return $employee_skill_token;

        $employee_filter = Employee::select('employees.name as emp_name', 'employees.email', 'employees.contact_no', 'employees.image', 'departments.name as department_name')
            ->join('departments', 'departments.token', '=', 'employees.department')
            ->whereIn('employees.token', $employee_skill_token)
            ->get();

        $employee_filter_count = count($employee_filter);


        if ($employee_filter_count >= 1) {
            return response()->json([
                'user_image' => $user->image,
                'user_name' => $user->name,
                'Total count' => $employee_filter_count,
                'filter_list' => $employee_filter
            ]);
        } else {
            return response()->json([
                'Message' => 'No data in this department'
            ]);
        }
    }


    public function show_employee(Request $request)
    {
        //
        $employee_view = Employee::select('employees.token', 'employees.name', 'employees.email', 'employees.contact_no', 'employees.dob', 'employees.blood_group', 'employees.address', 'employees.image', 'departments.name as department_name')
            ->join('departments', 'employees.department', '=', 'departments.token')->where('employees.token', $request->token)
            ->first();

        $employeeTokens = $employee_view->token;

        $emp_skills = Skills::select('skills.name', 'employee_skills.employee_token')
            ->join('employee_skills', 'skills.token', '=', 'employee_skills.skills_token')
            ->where('employee_skills.employee_token', $employeeTokens)
            ->get();


        if ($employee_view) {
            $arr = [];
            $obj = new StdClass;
            $obj->name = $employee_view->name;
            $obj->email = $employee_view->email;
            $obj->contact_no = $employee_view->contact_no;
            $obj->department_name = $employee_view->department_name;
            $obj->image = $employee_view->image;
            $obj->dob = $employee_view->dob;
            $obj->blood_group = $employee_view->blood_group;
            $obj->address = $employee_view->address;

            $obj->skills = [];

            $arr[$employee_view->token] = $obj;
            foreach ($emp_skills as $emp_skill) {
                $employeeToken = $emp_skill->employee_token;
                $arr[$employeeToken]->skills[] = $emp_skill->name;
            }
            return response()->json([
                $obj
            ]);
        }
    }


    public function update_employee(Request $request)
    {
        //
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            // 'email' => 'required|email',
            // 'contact_no' => 'required|string|max:10|min:10',
            // 'dob' => 'required|date',
            // 'blood_group' => 'required|string',
            // 'address' => 'required|string',
            // 'image' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'Error' => $validate->messages()
            ]);
        } else {

            $image = "";
            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('update', 'public');
            } else {
                $image = "null";
            }
            $employee_update = Employee::where('token', $request->token)->update([

                'name' => $request->name,
                'email' => $request->email,
                'department' => $request->department,
                'contact_no' => $request->contact_no,
                'dob' => $request->dob,
                'blood_group' => $request->blood_group,
                'address' => $request->address,
                'image' => $image,
            ]);

            return response()->json([
                'name' => $request->name,
                'email' => $request->email,
                'department' => $request->department,
                'contact_no' => $request->contact_no,
                'dob' => $request->dob,
                'blood_group' => $request->blood_group,
                'address' => $request->address,
                'image' => $image,
            ]);
        }
    }


    public function destroy_employee(Request $request)
    {

        $token = $request->input('token');


        if (empty($token)) {
            return response()->json(['Message' => 'Invalid input'], 400);
        }


        $employee_delete_count = Employee::where('token', $token)->count();
        if ($employee_delete_count === 0) {
            return response()->json(['Message' => 'No record found'], 404);
        }else{
            EmployeeSkills::whereIn('employee_token', $token)->delete();
        Employee::whereIn('token', $token)->delete();

        return response()->json(['Message' => 'Record deleted successfully'], 200);
        }



    }



    public function profile()
    {
        // $profile=User::select('name','image','email');
        $user = Auth::guard('emp')->user();
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'image' => $user->image
        ]);
    }
    public function search_employee($name)
    {
        $user = Auth::guard('emp')->user();
        $search_employee = Employee::select('employees.name', 'employees.email', 'employees.contact_no', 'employees.image', 'departments.name as department name')
            ->join('departments', 'employees.department', '=', 'departments.token')
            ->join('employee_skills', 'employees.token', '=', 'employee_skills.employee_token')
            ->join('skills', 'skills.token', '=', 'employee_skills.skills_token')
            ->where(function ($join) use ($name) {
                $join->where('employees.name', 'LIKE', '%' . $name . '%')
                    ->orWhere('departments.name', 'LIKE', '%' . $name . '%');
            })->distinct()->get();

        // return $search_employee;
        $search_count = count($search_employee);
        if ($search_count) {
            return response()->json([
                'user_image' => $user->image,
                'user_name' => $user->name,
                'Total_count' => $search_count,
                'search' => $search_employee
            ], 200);
        } else {
            return response()->json([
                'Total_count' => 0,
                'search' => 'No record Found'
            ], 404);
        }
    }
}
