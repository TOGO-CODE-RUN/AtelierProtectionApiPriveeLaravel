<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PharIo\Manifest\AuthorCollection;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post("/register", [UserController::class,"store"])->name("user_register");
Route::post("/login", [UserController::class,"login"])->name("user_login");



Route::get("/notes", [NoteController::class,"list"])->name("notes_list");

Route::middleware('auth:sanctum')->group(function () {
    // NOTE
    Route::post("/note/create", [NoteController::class,"store"])->name("note_create");
    Route::get("/user/notes", [NoteController::class,"index"])->name("user_notes_list");
    
});



/*
    Le middleware throttle est utilisé pour limiter le nombre de requêtes à l'API 
    par utilisateur. Il prend deux arguments : le nombre de requêtes autorisées 
    et la durée en minutes pendant laquelle ces requêtes sont autorisées.

    Dans cet exemple, throttle:60,1 limite l'utilisateur à 60 requêtes par minute 
    pour les routes protégées par Sanctum.
*/
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});