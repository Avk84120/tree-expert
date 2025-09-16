<?php

namespace app\Http\Controllers\admin\TreeWebController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Project;
use App\Models\Tree;
use App\Models\Plantation;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ProjectsController;
use App\Http\Controllers\Admin\TreeWebController;
use App\Http\Controllers\Admin\MediaController;
// use App\Http\Controllers\Admin\TreeController;
use App\Http\Controllers\Admin\TreeNameController;
use App\Http\Controllers\Admin\MapTreeController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/dashboard', function () {
    return view('dashboard', [
        'projects' => Project::count(),
        'trees' => Tree::count(),
        'plantations' => Plantation::count(),
    ]);
})->middleware(['auth'])->name('dashboard');

//Logout Route
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Projects Routes

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('projects', ProjectsController::class);
    Route::get('trees/export/excel', [TreeWebController::class, 'exportExcel'])->name('trees.export');
    Route::get('trees/export/kml', [TreeWebController::class, 'exportKml'])->name('trees.export.kml');
    Route::post('trees/import', [TreeWebController::class, 'importExcel'])->name('trees.import');
    Route::get('trees/geotag', [TreeWebController::class, 'geotagForm'])->name('trees.geotag');
    Route::post('trees/geotag', [TreeWebController::class, 'geotag'])->name('trees.geotag.submit');
    Route::get('admin/trees/{tree}/geotag', [TreeController::class, 'geotag'])
     ->name('admin.trees.geotag');
    Route::get('admin/trees/{tree}/geotag', [TreeController::class, 'geotagForm'])
     ->name('admin.trees.geotag.form');
    Route::get('trees/search', [TreeWebController::class, 'search'])->name('trees.search');
    // Extra routes
    Route::post('admin/projects/{project}/assign-user', [ProjectsController::class, 'assignUser'])
        ->name('projects.assignUser');
    Route::get('admin/projects/{project}/assign-user', [ProjectsController::class, 'showAssignForm'])
        ->name('projects.showAssignForm');
    Route::get('projects/{project}/settings', [ProjectsController::class, 'settings'])
        ->name('projects.settings');
    Route::put('projects/{project}/settings', [ProjectsController::class, 'updateSettings'])
        ->name('projects.updateSettings');
    Route::get('admin/projects/{project}/edit', [ProjectsController::class, 'edit'])->name('admin.projects.edit');
    // Route::get('admin/trees', [TreeWebController::class], 'index')->name('admin.trees.index');

});

//Media Routes
Route::prefix('media')->group(function () {
    Route::get('upload-tree', [MediaController::class, 'showUploadTreeForm'])->name('media.upload.tree');
    Route::post('upload-tree', [MediaController::class, 'uploadTreePhoto']);

    Route::get('upload-aadhaar', [MediaController::class, 'showUploadAadhaarForm'])->name('media.upload.aadhaar');
    Route::post('upload-aadhaar', [MediaController::class, 'uploadAadhaarPhoto']);

    Route::get('/media/reports', [MediaController::class, 'generateReports'])->name('admin.media.reports');
    Route::get('download-excel', [MediaController::class, 'downloadExcel'])->name('media.download.excel');
    Route::get('download-kml', [MediaController::class, 'downloadKML'])->name('media.download.kml');
    Route::get('generate-reports', [MediaController::class, 'generateReports'])->name('media.generate.reports');
    Route::get('/tree-photos', [MediaController::class, 'listTreePhotos'])->name('media.tree_photos.index');

});

//Tree Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('trees', TreeWebController::class);

});

// Tree Names master routes
Route::get('/tree-names', [TreeNameController::class, 'index'])->name('tree.names.index');
Route::post('/tree-names', [TreeNameController::class, 'store'])->name('tree.names.store');
Route::put('/tree-names/{id}', [TreeNameController::class, 'update'])->name('tree.names.update');
Route::delete('/tree-names/{id}', [TreeNameController::class, 'destroy'])->name('tree.names.destroy');


// Map Tree
Route::get('/map-trees', [MapTreeController::class, 'index'])->name('map.trees.index');
require __DIR__.'/auth.php';
