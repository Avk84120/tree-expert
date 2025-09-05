<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ProjectsController;
use App\Http\Controllers\Api\TreesController;
use App\Http\Controllers\Api\PlantationsController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TreeNamesController; 
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\FirebaseOtpController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

// Public Routes
// -----------------------
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/otp/send', [AuthController::class, 'sendOtp']);

//Firebase otp
Route::post('auth/otp/send', [FirebaseOtpController::class, 'sendOtp']);
Route::post('auth/otp/verify', [FirebaseOtpController::class, 'verifyOtp']);
//Password forget
Route::post('auth/forgot-password', [ForgotPasswordController::class, 'forgot']);
Route::post('auth/reset-password', [ForgotPasswordController::class, 'reset']);

// User Role & Permission API (Admin / Officer / Employee)
Route::middleware(['auth:sanctum','role:admin'])->group(function () {
    Route::get('roles', [RolesController::class, 'index']);          // list roles
    Route::post('roles', [RolesController::class, 'store']);         // create role
    Route::delete('roles/{id}', [RolesController::class, 'destroy']); // delete role
    Route::post('users/{id}/assign-role', [RolesController::class, 'assignRole']); // assign role
    Route::get('users/{id}/role', [RolesController::class, 'userRole']);           // get user role
});

//User Active/Deactive
Route::middleware(['auth:sanctum','role:admin'])->group(function () {
    Route::patch('users/{id}/status', [UsersController::class, 'toggleStatus']);
});

//	User Profile Update 
Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('profile', [ProfileController::class, 'update']); // update profile
});

// Roles
// Only admin can access user list
Route::middleware(['auth:sanctum','role:admin'])->group(function () {
    Route::get('users', [\App\Http\Controllers\Api\UsersController::class, 'index']);
    Route::delete('users/{id}', [\App\Http\Controllers\Api\UsersController::class, 'destroy']);
});


// Admin and officer can manage projects
Route::middleware(['auth:sanctum','role:admin,officer'])->group(function () {
    Route::apiResource('projects', ProjectsController::class);
});

// All authenticated users (employee included) can manage trees
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('trees', \App\Http\Controllers\Api\TreesController::class);
});
// -----------------------
// Protected Routes (Require Auth)
// -----------------------
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Users
    Route::get('users', [UsersController::class, 'index']);
    Route::get('users/{id}', [UsersController::class, 'show']);
    Route::put('users/{id}', [UsersController::class, 'update']);
    Route::patch('users/{id}/status', [UsersController::class, 'toggleStatus']);
    Route::delete('users/{id}', [UsersController::class, 'destroy']);

    // Projects
    Route::middleware(['auth:sanctum','role:admin,officer'])->group(function () {
    Route::post('projects/{id}/assign-user', [\App\Http\Controllers\Api\ProjectsController::class, 'assignUser']);
    });

    Route::middleware(['auth:sanctum','role:admin,officer'])->group(function () {
    Route::get('projects', [\App\Http\Controllers\Api\ProjectsController::class, 'index']);
    });

    Route::apiResource('projects', ProjectsController::class);
    Route::get('projects/{id}/settings', [ProjectsController::class, 'settings']);
    Route::put('projects/{id}/settings', [ProjectsController::class, 'updateSettings']);

    // Trees
    Route::apiResource('trees', TreesController::class);
    Route::get('trees/export/kml', [TreesController::class, 'exportKml']);

    // Plantations
    Route::apiResource('plantations', PlantationsController::class);
    Route::post('plantations/{id}/trees', [PlantationsController::class, 'addTree']);
    Route::delete('plantations/{id}/trees/{treeId}', [PlantationsController::class, 'removeTree']);
    Route::post('plantations/{id}/photos', [PlantationsController::class, 'uploadPhotos']);

    // Tree Names (Master List)
    Route::apiResource('tree-names', TreeNamesController::class);

    // Uploads
    Route::post('upload/aadhaar', [UploadController::class, 'uploadAadhaar']);

    // Reports
    Route::get('reports/master', [ReportsController::class, 'masterReport']);
    Route::get('reports/export/excel', [ReportsController::class, 'exportExcel']);
    Route::get('reports/export/pdf', [ReportsController::class, 'exportPdf']);

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);
});



