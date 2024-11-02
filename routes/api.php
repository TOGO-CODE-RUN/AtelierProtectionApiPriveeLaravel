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

Route::get('/test', function(){
    return response()->json([
        "message" => "Hello World",
        "success" => true,
    ]);
});