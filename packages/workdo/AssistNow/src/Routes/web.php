<?php

use Workdo\AssistNow\Http\Controllers\AssistNowController;
use Illuminate\Support\Facades\Route;
use Workdo\AssistNow\Http\Controllers\ClientController;
use Workdo\AssistNow\Http\Controllers\DebtorController;
use Workdo\AssistNow\Http\Controllers\ServiceController;
use Workdo\AssistNow\Http\Controllers\TaskAssignmentController;
use Workdo\AssistNow\Http\Controllers\StaffReportController;

Route::middleware(['web'])->group(function ()
{
    Route::group(['middleware' => ['auth', 'verified', 'PlanModuleCheck:AssistNow']], function () {
        Route::get('dashboard/assistnow', [AssistNowController::class, 'index'])->name('assistnow.dashboard');
        Route::get('/staff-reports', [StaffReportController::class, 'index'])->name('staff-reports.index');
        Route::resource('assistnow-services', ServiceController::class);
        Route::resource('assistnow-debtors', DebtorController::class);
        Route::resource('assistnow-clients', ClientController::class);
        Route::resource('task-assignments', TaskAssignmentController::class);        
    });
});