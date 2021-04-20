<?php

use App\Http\Resources\UpcomingResource;
use App\Models\Today;
use App\Models\Upcoming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get("/upcoming", function () {
    return UpcomingResource::collection(Upcoming::all());
});
Route::post("/upcoming", function (Request $req) {
    return Upcoming::create([
        'title' => $req->title,
        'taskId' => $req->taskId,
        'waiting' => $req->waiting,
    ]);
});
Route::delete("/upcoming/{taskId}", function ($taskId) {
    DB::table('upcomings')->where('taskId', $taskId)->delete();
    return 204;
});
Route::post("/dailytask", function (Request $req) {
    return Today::create([
        'title' => $req->title,
        'taskId' => $req->taskId,
        'id' => $req->id,
    ]);
});
Route::delete("/dailytask/{taskId}", function ($taskId) {
    DB::table('todays')->where('taskId', $taskId)->delete();
    return 204;
});
