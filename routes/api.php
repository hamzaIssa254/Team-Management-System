<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {

    /**
     * Auth Routes
     *
     * These routes handle user authentication, including login, registration, and logout.
    */
    Route::controller(AuthController::class)->group(function () {
        /**
         * Login Route
         *
         * @method POST
         * @route /v1/login
         * @desc Authenticates a user and returns a JWT token.
         */
        Route::post('login', 'login');

        /**
         * Register Route
         *
         * @method POST
         * @route /v1/register
         * @desc Registers a new user and returns a JWT token.
         */
        Route::post('register', 'register');

        /**
         * Logout Route
         *
         * @method POST
         * @route /v1/logout
         * @desc Logs out the authenticated user.
         * @middleware auth:api
         */
        Route::post('logout', 'logout')->middleware('auth:api');
    });

    // resource for Task CRUD
    Route::apiResource('tasks', TaskController::class)->middleware(['auth:api','manager']);
    Route::apiResource('projects', ProjectController::class)->middleware(['auth:api','manager']);
    Route::get('user-tasks',[TaskController::class,'userTasks'])->middleware('auth:api');
    Route::post('projects/{project}/contribution', [TaskController::class, 'updateContribution'])->middleware('auth:api');
    Route::post('projects/{project}/tasks/{task}/complete', [TaskController::class, 'completeTask'])->middleware(['auth:api','developer']);
    Route::post('projects/{project}/tasks/{task}/add-note', [TaskController::class, 'addNote'])->middleware(['auth:api','tester']);
    Route::put('update-pivot/{id}',[TaskController::class,'updateTaskData'])->middleware(['auth:api','manager']);
});
