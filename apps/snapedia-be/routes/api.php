<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{
    AuthController,
    UserController,
    PremiumController,
    ArticleController,
    CategoryController,
    LikeController,
    SaveController,
    CommentController,
    FollowerController,
    WriterTestController,
    AdminController
};

Route::prefix('v1')->middleware('api')->group(function () {
    // 🔄 Ping test
    Route::get('/ping', fn () => response()->json(['pong' => true]));

    // 🔐 Auth
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    
    // 🔒 Rotte protette da Sanctum
    Route::middleware('auth:sanctum')->group(function () {

        // 📦 Risorse principali
        Route::apiResource('users', UserController::class);
        Route::apiResource('premium', PremiumController::class);
        Route::apiResource('articles', ArticleController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('likes', LikeController::class);
        Route::apiResource('saves', SaveController::class);
        Route::apiResource('comments', CommentController::class);
        Route::apiResource('followers', FollowerController::class);
        Route::apiResource('writer-tests', WriterTestController::class);

        // 👑 Rotte riservate all'admin
        Route::middleware('is_admin')->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
        });
    });
});
