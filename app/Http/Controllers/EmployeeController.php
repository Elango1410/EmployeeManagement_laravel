<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::guard('emp')->user();
        $employees = Employee::select('employees.name', 'employees.email', 'employees.contact_no', 'employees.image', 'departments.name as department name')
            ->join('departments', 'employees.department', '=', 'departments.token')->where('user_token', $user->token)
            ->get();
        $employee_count = count($employees);
        if ($employees) {
            return response()->json([
                'user_image' => $user->image,
                'user_name' => $user->name,
                'employee_count' => $employee_count,
                'employee_list' => $employees
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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

            if ($employee) {
                return response()->json([
                    'Message' => 'One Record created successfully',
                    'Emaployee' => $employee
                ]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function filter(Request $request)
    {
        //
        $employees_filter = Employee::select('employees.name', 'employees.email', 'departments.name as department_name', 'employees.contact_no', 'employees.dob', 'employees.blood_group', 'employees.address', 'employees.image')
            ->join('departments', 'departments.token', '=', 'employees.department')
            ->where('departments.name', $request->name)
            ->orWhere('employees.blood_group', $request->blood_group)
            ->get();
        $employee_filter_count = count($employees_filter);
        if ($employee_filter_count >= 1) {
            return response()->json([
                'Total count' => $employee_filter_count,
                'filter_list' => $employees_filter
            ]);
        } else {
            return response()->json([
                'Message' => 'No data '
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
        $employee_view = Employee::select('employees.name', 'employees.email', 'employees.contact_no', 'employees.dob', 'employees.blood_group', 'employees.address', 'employees.image', 'departments.name as department name')
            ->join('departments', 'employees.department', '=', 'departments.token')->where('employees.token', $request->token)
            ->get();
        if (count($employee_view) > 0) {
            return response()->json([
                'Employee_view' => $employee_view
            ]);
        } else {
            return response()->json([
                'Message' => 'No record Found'
            ]);
        }
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
    public function update(Request $request)
    {
        //
        $validate = Validator::make($request->all(), [
            // 'name' => 'required|string|min:3',
            'email' => 'required|email',
            'department' => 'required',
            'contact_no' => 'required|string|max:10|min:10',
            'dob' => 'required|date',
            'blood_group' => 'required|string',
            'address' => 'required|string',
            'image' => 'required',
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $employee_delete_count = Employee::select()->where('token', $request->token)->count();
        if ($employee_delete_count > 0) {
            $employee_delete = Employee::select()->where('token', $request->token)->delete();
            return response()->json([
                'Message' => 'Record deleted successfully'
            ]);
        } else {
            return response()->json([
                'Message' => 'No record found'
            ]);
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
    public function search($name)
    {
        $search_employee = Employee::select('employees.name', 'employees.email', 'employees.contact_no', 'employees.image', 'departments.name as department name')
            ->join('departments', 'employees.department', '=', 'departments.token')
            ->where(function ($join) use ($name) {
                $join->where('employees.name', 'LIKE', '%' . $name . '%')->orWhere('employees.blood_group', 'LIKE', '%' . $name . '%')
                    ->orWhere('departments.name', 'LIKE', '%' . $name . '%')
                    ->orWhere('employees.email', 'LIKE', '%' . $name . '%');
            })->get();

        $search_count = count($search_employee);
        if ($search_count) {
            return response()->json([
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
