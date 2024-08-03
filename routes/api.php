<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\DelayReportController;
use App\Models\DelayReport;

Route::post('/agents/{user}/assign-delay-report', [AgentController::class, 'assignDelayReport'])->name('agent.assign-delay-report');
Route::post('/orders/{order}/delay-report', [DelayReportController::class, 'store'])->name('order.delay-report');
Route::get('/delay-report/analyse', [DelayReportController::class, 'analyse']);
