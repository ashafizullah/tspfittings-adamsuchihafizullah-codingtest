<?php

use App\Http\Controllers\Apps\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apps\PermissionController;
use App\Http\Controllers\Apps\RoleController;
use App\Http\Controllers\Apps\UserController;
use App\Http\Controllers\Apps\WorkOrderController;
use App\Http\Controllers\Apps\WorkOrderProgressController;

Route::get('/', function () {
	return \Inertia\Inertia::render('Auth/Login');
})->name('login');

//prefix "apps"
Route::prefix('apps')->group(function () {

	//middleware "auth"
	Route::group(['middleware' => ['auth']], function () {

		//route welcome
		Route::get('/welcome', [DashboardController::class, 'welcome'])->name('apps.dashboard.welcome')->middleware('permission:welcome.index');

		//route permissions
		Route::get('/permissions', PermissionController::class)->name('apps.permissions.index')->middleware('permission:permissions.index');

		//route roles
		Route::resource('/roles', RoleController::class, ['as' => 'apps'])->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

		//route users
		Route::resource('/users', UserController::class, ['as' => 'apps'])->middleware('permission:users.index|users.create|users.edit|users.delete');

        //route assigned work orders for operators
        Route::get('/work-orders/assigned', [WorkOrderController::class, 'viewAssigned'])
        ->name('apps.work-orders.assigned')
        ->middleware('permission:work_orders.view_assigned');

        // Route for work order reports
        Route::get('/work-orders/reports', [WorkOrderController::class, 'reports'])
            ->name('apps.work-orders.reports')
            ->middleware('permission:work_orders.view_reports');

        // New routes for work order progress
        // View progress for a specific work order
        Route::get('/work-orders/{workOrder}/progress', [WorkOrderProgressController::class, 'index'])
            ->name('apps.work-orders.progress')
            ->middleware('permission:work_orders.view_assigned');

        // Submit new progress update
        Route::post('/work-orders/{workOrder}/progress', [WorkOrderProgressController::class, 'store'])
            ->name('apps.work-orders.progress.store')
            ->middleware('permission:work_orders.update_status');

        // Get production steps timeline (optional API endpoint)
        Route::get('/work-orders/{workOrder}/production-steps', [WorkOrderProgressController::class, 'getProductionSteps'])
        ->name('apps.work-orders.production-steps')
        ->middleware('permission:work_orders.view_assigned');

        //route update work order status
        Route::post('/work-orders/{workOrder}/update-status', [WorkOrderController::class, 'updateStatus'])
        ->name('apps.work-orders.update-status')
        ->middleware('permission:work_orders.update_status');

        //route work orders
        Route::resource('/work-orders', WorkOrderController::class, ['as' => 'apps'])
        ->middleware('permission:work_orders.index|work_orders.create|work_orders.edit|work_orders.delete');
	});
});
