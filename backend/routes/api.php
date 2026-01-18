<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelOrderController;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Travel Orders
    Route::get('/travel-orders', [TravelOrderController::class, 'index']);
    Route::post('/travel-orders', [TravelOrderController::class, 'store']);
    Route::get('/travel-orders/{id}', [TravelOrderController::class, 'show']);
    Route::patch('/travel-orders/{id}/status', [TravelOrderController::class, 'updateStatus']);

    // Notifications
    Route::get('/notifications/unread', function (Request $request) {
        return $request->user()
            ->notifications()
            ->whereNull('read_at')
            ->latest()
            ->get();
    });

    Route::post('/notifications/{id}/read', function ($id, Request $request) {
        $request->user()
            ->notifications()
            ->where('id', $id)
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);

    });

});
