<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MessageController;

// Web routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [HomeController::class, 'jobs'])->name('jobs');
Route::get('/jobs/{id}', [HomeController::class, 'jobDetail'])->name('job.detail');
Route::get('/admin', [HomeController::class, 'admin'])->name('admin');

// API routes
Route::prefix('api')->group(function () {
    Route::apiResource('jobs', JobController::class);
    Route::apiResource('applications', ApplicationController::class);
    Route::apiResource('testimonials', TestimonialController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('messages', MessageController::class);
    
    // Additional API endpoints
    Route::post('contact', [MessageController::class, 'contact']);
    Route::patch('applications/{id}/status', [ApplicationController::class, 'updateStatus']);
    Route::patch('messages/{id}/read', [MessageController::class, 'markAsRead']);
});
