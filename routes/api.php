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
use App\Http\Controllers\Api\LocationController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Api\ProjectDetailController;
use App\Http\Controllers\Api\TreePhotoController;
use App\Http\Controllers\Api\MapController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\VideoTutorialController;
use App\Http\Controllers\Api\PrivacyPolicyController;
use App\Http\Controllers\Api\ContactController;
use App\Imports\TreesImport;
use Maatwebsite\Excel\Facades\Excel;


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

//States and Cities
Route::get('states', [LocationController::class, 'states']);
Route::get('states/{id}/cities', [LocationController::class, 'cities']);

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


    //Projects Details Screen API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('projects/{id}/details', [ProjectDetailController::class, 'show']);
    Route::get('projects/{id}/kml', [ProjectDetailController::class, 'exportKml']); // KML with photo links
    Route::get('projects/{id}/export/excel', [ProjectDetailController::class, 'exportExcel']); // ?unit=cm|m|feet
    Route::get('projects/{id}/photos/download', [ProjectDetailController::class, 'downloadPhotos']); // zip
    Route::get('projects/{id}/report', [ProjectDetailController::class, 'report']); // JSON (or PDF)
});

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
    Route::post('trees/import', [TreesController::class, 'importExcel']);
    Route::post('trees/geotag', [TreesController::class, 'geotag']);

    //Trees Export Data With Excel
    Route::middleware('auth:sanctum')->group(function () {
    Route::get('trees/export/excel/{projectId?}', [\App\Http\Controllers\Api\TreesController::class, 'exportExcel']);
});

//Download KML with photo links
Route::middleware('auth:sanctum')->group(function () {
    Route::get('trees/export/kml/{projectId?}', [\App\Http\Controllers\Api\KmlExportController::class, 'downloadKml']);
});
//•	Generate Reports API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('projects/{id}/report', [\App\Http\Controllers\Api\ReportsController::class, 'generateReport']);
});

//Tree name master list
Route::middleware('auth:sanctum')->group(function () {
    // Tree Name Master
    Route::get('tree-names', [TreeNamesController::class, 'index']);
    Route::post('tree-names', [TreeNamesController::class, 'store']);
    Route::put('tree-names/{id}', [TreeNamesController::class, 'update']);

    // Map Trees
    Route::get('map/trees', [MapController::class, 'treesOnMap']);

    // FAQ
    Route::get('faqs', [FaqController::class, 'index']);
    Route::post('faqs', [FaqController::class, 'store']);

    // Video Tutorials
    Route::get('videos', [VideoTutorialController::class, 'index']);
    Route::post('videos', [VideoTutorialController::class, 'store']);

    // Privacy Policy
    Route::get('privacy-policy', [PrivacyPolicyController::class, 'show']);
    Route::post('privacy-policy', [PrivacyPolicyController::class, 'update']);

    // Contact
    Route::get('contacts', [ContactController::class, 'index']);
    Route::post('contacts', [ContactController::class, 'store']);
});



    
    // Plantations
    Route::apiResource('plantations', PlantationsController::class);
    Route::post('plantations/{id}/trees', [PlantationsController::class, 'addTree']);
    Route::delete('plantations/{id}/trees/{treeId}', [PlantationsController::class, 'removeTree']);
    Route::post('plantations/{id}/photos', [PlantationsController::class, 'uploadPhotos']);

    // Tree Names (Master List)
    Route::apiResource('tree-names', TreeNamesController::class);

    // Tree Photos Upload API 
    Route::middleware('auth:sanctum')->group(function () {
    Route::post('trees/photos/upload', [\App\Http\Controllers\Api\TreePhotoController::class, 'upload']);
});


    // Uploads aadhaar
Route::middleware('auth:sanctum')->group(function () {
    Route::post('user/upload-aadhaar', [\App\Http\Controllers\Api\UsersController::class, 'uploadAadhaar']);
});

    // Reports
    Route::get('reports/master', [ReportsController::class, 'masterReport']);
    Route::get('reports/export/excel', [ReportsController::class, 'exportExcel']);
    Route::get('reports/export/pdf', [ReportsController::class, 'exportPdf']);
    Route::get('reports/export/kml', [ReportsController::class, 'exportKml']);

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);
});



