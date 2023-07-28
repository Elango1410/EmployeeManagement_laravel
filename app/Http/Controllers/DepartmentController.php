<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use stdClass;

date_default_timezone_set('Asia/Kolkata');

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function department_list()
    {
        //
        $department_list = Department::select('name', 'token')->selectRaw('date_format(created_at, "created on %d-%b-%Y") as date')
            ->selectRaw('date_format(created_at, "%l:%i,%p") as time')->get();
        $department_list_count = count($department_list);
        if ($department_list_count > 0) {
            return response()->json([
                'department_list_count' => $department_list_count,
                'Department_list' => $department_list
            ]);
        } else {
            return response()->json([
                'Department_list' => 'No data found in table'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create_department(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|array|min:1',
            'name.*' => 'required|string|min:3'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'Error' => $validate->messages()
            ]);
        } else {

            $departments = [];
            foreach ($request['name'] as $name) {
                $department = Department::create([
                    'name' => $name,
                    'token' => rand(100000, 999999)
                ]);

                $departments[] = $department;
            }

            return response()->json([
                'Departments' => $departments
            ]);
        }

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
    public function show_department(Request $request)
    {
        //
        $department_view = Department::select('name')->where('token', $request->token)->first();
        return response()->json([
            'department_view' => $department_view
        ]);
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
    public function update_department(Request $request)
    {
        //
        $department_update = Department::where('token', $request->token)->update([
            'name' => $request->name
        ]);

        return response()->json([
            'update' => $request->name . ' updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_department(Request $request)
    {
        //
        $depart_delete_count = Department::select('token')->where([['token', $request->token]])->count();

        if ($depart_delete_count == 0) {
            return response()->json([
                'message' => 'No Record founded'
            ]);
        } else {
            $depart_delete = Department::select('token')->where([['token', $request->token]])->delete();

            return response()->json([
                'message' => "deleted successfully"
                // 'echo'=> $task
            ]);
        }
    }

    public function search_department($name)
    {
        $department_search = Department::select('name', 'token')->selectRaw('date_format(created_at, "created on %d-%b-%Y") as date')
            ->selectRaw('date_format(created_at, "%l:%i,%p") as time')->where('name', 'LIKE', '%' . $name . '%')->get();
        $department_search_count = count($department_search);
        if ($department_search_count > 0) {
            return response()->json([
                'total_count' =>  $department_search_count,
                'Department_list' => $department_search
            ]);
        } else {
            return response()->json([
                'Department_list' => 'No data found in table'
            ]);
        }
    }


    public function dept_emp(Request $request)
    {

        $arr = array();
        $depart_name = Department::select('departments.name')->where('departments.token', $request->token)->first();
        if ($depart_name) {

            $arr['department'] = $depart_name->name;
        }

        $dept = Department::select('employees.image', 'employees.name', 'employees.email', 'employees.contact_no', 'employees.address')
            ->join('employees', 'employees.department', '=', 'departments.token')
            ->where('departments.token', $request->token)
            ->get();

        $dept_count = count($dept);

        if ($dept_count > 0) {

            foreach ($dept as $depart) {
                $obj = new StdClass;
                $obj->image = $depart->image;
                $obj->name = $depart->name;
                $obj->email = $depart->email;
                $obj->contact_no = $depart->contact_no;
                $obj->address = $depart->address;
                $arr['employees'][] = $obj;
                // array_push($arr,$obj);
            }

            return response()->json([
                'Total count' => $dept_count,
                'data' => $arr
            ]);
        } else {
            return response()->json([
                'Total count' => $dept_count,
                'message' => 'No data'
            ]);
        }
    }
}
