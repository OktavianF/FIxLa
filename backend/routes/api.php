<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CostEstimationController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('v1')->group(function () {
  Route::post('/register', [AuthController::class , 'register']);
  Route::post('/login', [AuthController::class , 'login']);

  // Public report viewing
  Route::get('/reports', [ReportController::class , 'index']);
  Route::get('/reports/map', [ReportController::class , 'mapReports']);
  Route::get('/reports/{report}', [ReportController::class , 'show']);

  // Serve images with CORS (workaround for php artisan serve)
  Route::get('/images/{path}', function (string $path) {
      $file = storage_path('app/public/' . $path);
      if (!file_exists($file)) abort(404);
      return response()->file($file);
  })->where('path', '.*');
});

// Authenticated routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
  // Auth
  Route::post('/logout', [AuthController::class , 'logout']);
  Route::get('/me', [AuthController::class , 'me']);

  // Reports
  Route::post('/reports', [ReportController::class , 'store']);
  Route::get('/my-reports', [ReportController::class , 'myReports']);

  // Notifications
  Route::get('/notifications', [NotificationController::class , 'index']);
  Route::get('/notifications/unread-count', [NotificationController::class , 'unreadCount']);
  Route::patch('/notifications/{notification}/read', [NotificationController::class , 'markAsRead']);
  Route::patch('/notifications/read-all', [NotificationController::class , 'markAllAsRead']);

  // Admin / Government Dashboard
  Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->prefix('admin')->group(function () {
      Route::get('/dashboard/overview', [DashboardController::class , 'overview']);
      Route::get('/dashboard/reports-by-district', [DashboardController::class , 'reportsByDistrict']);
      Route::get('/dashboard/damage-distribution', [DashboardController::class , 'damageDistribution']);
      Route::get('/dashboard/priority-ranking', [DashboardController::class , 'priorityRanking']);
      Route::get('/dashboard/heatmap', [DashboardController::class , 'heatmapData']);
      Route::get('/dashboard/monthly-trend', [DashboardController::class , 'monthlyTrend']);
      Route::patch('/reports/{report}/status', [ReportController::class , 'updateStatus']);
      Route::delete('/reports/{report}', [ReportController::class , 'destroy']);
      Route::post('/cost-estimation', [CostEstimationController::class , 'estimate']);
    }
    );
  });