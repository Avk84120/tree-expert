<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ProjectsController;
use App\Http\Controllers\Api\TreesController;
use App\Http\Controllers\Api\PlantationsController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TreeNamesController; 
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



