<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    UserController,
    ArticleController,
    CategoryController,
    CommentController,
    LikeController,
    SaveController,
    UserCategoryPreferenceController,
    PremiumSubscriptionController,
    SnapwriterTestController,
    SubscriptionCancellationController
};

Route::middleware('api')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('articles', ArticleController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('likes', LikeController::class)->only(['store', 'destroy']);
    Route::apiResource('saves', SaveController::class)->only(['store', 'destroy']);
    Route::apiResource('preferences', UserCategoryPreferenceController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('subscriptions', PremiumSubscriptionController::class);
    Route::apiResource('snapwriter-tests', SnapwriterTestController::class)->only(['index', 'store']);
    Route::apiResource('subscription-cancellations', SubscriptionCancellationController::class)->only(['store']);
});
