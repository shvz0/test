<?php

use App\Http\Controllers\ShowsAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("shows")->group(function () {
    Route::get("/", [ShowsAPIController::class, "shows"]);
    Route::get("/{showID}/events", [ShowsAPIController::class, "showEvents"])->where('showID', '\d+');
});

Route::prefix("events")->group(function () {
    Route::get("/{eventID}/places", [ShowsAPIController::class, "showPlaces"])->where('eventID', '\d+');
    Route::post("/{eventID}/reserve", [ShowsAPIController::class, "reservePlaces"])->where('eventID', '\d+');
});
