<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['middleware' => ['auth:sanctum']], function() {

Route::post('user/logout',[UserController::class,'logout_user']);
Route::post('user/change_password',[UserController::class,'change_password']);
Route::post('user/update_user',[UserController::class,'update_user']);

Route::post('skills/create',[SkillsController::class,'create_skills']);
Route::post('skills/list',[SkillsController::class,'list_skills']);
Route::post('skills/delete',[SkillsController::class,'remove_skill']);



Route::post('department/create',[DepartmentController::class,'create_department']);
Route::post('department/view',[DepartmentController::class,'show_department']);
Route::post('department/update',[DepartmentController::class,'update_department']);
Route::post('department/delete',[DepartmentController::class,'destroy_department']);
Route::post('department/search/{name}',[DepartmentController::class,'search_department']);
Route::post('department/depart',[DepartmentController::class,'dept_emp']);
Route::post('department/list',[DepartmentController::class,'department_list']);


Route::post('user/profile',[EmployeeController::class,'profile']);
Route::post('employee/list',[EmployeeController::class,'employee_list']);
Route::post('employee/create',[EmployeeController::class,'create_employee']);
Route::post('employee/view',[EmployeeController::class,'show_employee']);
Route::post('employee/update',[EmployeeController::class,'update_employee']);
Route::post('employee/delete',[EmployeeController::class,'destroy_employee']);
Route::post('employee/filter',[EmployeeController::class,'filter_employee']);
Route::post('employee/search/{name}',[EmployeeController::class,'search_employee']);
});


Route::post('forget-password', [UserController::class, 'forgetPassword']);
Route::post('user/register',[UserController::class,'register_user']);
Route::post('user/login',[UserController::class,'login_user']);


