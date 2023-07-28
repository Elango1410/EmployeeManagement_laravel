<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;

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

Route::post('user/logout',[UserController::class,'logout']);
Route::post('user/change_password',[UserController::class,'change_password']);

Route::post('department/create',[DepartmentController::class,'create']);
Route::post('department/view',[DepartmentController::class,'show']);
Route::post('department/update',[DepartmentController::class,'update']);
Route::post('department/delete',[DepartmentController::class,'destroy']);
Route::post('department/search/{name}',[DepartmentController::class,'search']);
Route::post('department/depart',[DepartmentController::class,'dept_emp']);
Route::post('department/list',[DepartmentController::class,'index']);


Route::post('user/profile',[EmployeeController::class,'profile']);
Route::post('employee/list',[EmployeeController::class,'index']);
Route::post('employee/create',[EmployeeController::class,'create']);
Route::post('employee/view',[EmployeeController::class,'show']);
Route::post('employee/update',[EmployeeController::class,'update']);
Route::post('employee/delete',[EmployeeController::class,'destroy']);
Route::post('employee/filter',[EmployeeController::class,'filter']);
Route::post('employee/search/{name}',[EmployeeController::class,'search']);
});



Route::post('user/register',[UserController::class,'register']);
Route::post('user/login',[UserController::class,'login']);
