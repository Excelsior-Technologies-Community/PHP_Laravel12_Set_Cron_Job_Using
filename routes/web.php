<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CronJobLogController;

Route::get('/', function () {
    return redirect()->route('categories.index');
});


/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
*/

Route::get(
    '/categories',
    [CategoryController::class, 'index']
)->name('categories.index');


Route::get(
    '/categories/create',
    [CategoryController::class, 'create']
)->name('categories.create');


Route::post(
    '/categories',
    [CategoryController::class, 'store']
)->name('categories.store');


Route::post(
    '/categories/bulk-delete',
    [CategoryController::class, 'bulkDestroy']
)->name('categories.bulk-destroy');


/*
|--------------------------------------------------------------------------
| Category Status
|--------------------------------------------------------------------------
*/

Route::patch(
    '/categories/{category}/toggle-status',
    [CategoryController::class, 'toggleStatus']
)->name('categories.toggle-status');


/*
|--------------------------------------------------------------------------
| Category Trash
|--------------------------------------------------------------------------
*/

Route::patch(
    '/categories/{id}/restore',
    [CategoryController::class, 'restore']
)->name('categories.restore');


Route::delete(
    '/categories/{id}/force-delete',
    [CategoryController::class, 'forceDestroy']
)->name('categories.force-delete');


/*
|--------------------------------------------------------------------------
| Category CSV Export
|--------------------------------------------------------------------------
*/

Route::get(
    '/categories-export',
    [CategoryController::class, 'exportCsv']
)->name('categories.export');


/*
|--------------------------------------------------------------------------
| Category Edit / Update / Delete
|--------------------------------------------------------------------------
*/

Route::get(
    '/categories/{category}/edit',
    [CategoryController::class, 'edit']
)->name('categories.edit');


Route::put(
    '/categories/{category}',
    [CategoryController::class, 'update']
)->name('categories.update');


Route::delete(
    '/categories/{category}',
    [CategoryController::class, 'destroy']
)->name('categories.destroy');


Route::get(
    '/categories/{category}',
    [CategoryController::class, 'show']
)->name('categories.show');


/*
|--------------------------------------------------------------------------
| Cron Job Monitoring
|--------------------------------------------------------------------------
*/

Route::get(
    '/cron-history',
    [CronJobLogController::class, 'index']
)->name('cron-history.index');


Route::get(
    '/cron-history/{cronJobLog}',
    [CronJobLogController::class, 'show']
)->name('cron-history.show');


Route::post(
    '/cron-history/run',
    [CronJobLogController::class, 'runNow']
)->name('cron-history.run');