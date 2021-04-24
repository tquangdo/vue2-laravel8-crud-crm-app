<?php

use App\Http\Resources\TodayTaskResource;
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
//U-1
Route::get("/upcoming", function () {
    return UpcomingResource::collection(Upcoming::all());
});
//U-2
Route::post("/upcoming", function (Request $req) {
    return Upcoming::create([
        'title' => $req->title,
        'taskId' => $req->taskId,
        'waiting' => $req->waiting,
    ]);
});
//U-3
Route::delete("/upcoming/{taskId}", function ($taskId) {
    DB::table('upcomings')->where('taskId', $taskId)->delete();
    return 204;
});
//D-1
Route::get("/dailytask", function () {
    // sortBy() KO dung duoc voi "TodayTaskResource extends JsonResource", dung OK voi "Illuminate\Support\Collection"
    // return TodayTaskResource::collection(Today::all())->sortBy('id')->values()->all();
    return TodayTaskResource::collection(Today::all());
});
//D-2
Route::post("/dailytask", function (Request $req) {
    return Today::create([
        'title' => $req->title,
        'taskId' => $req->taskId,
        'id' => $req->id,
    ]);
});
//D-3
Route::delete("/dailytask/{taskId}", function ($taskId) {
    DB::table('todays')->where('taskId', $taskId)->delete();
    return 204;
});
//D-4
Route::put("/dailytask/{taskId}", function (Request $req) {
    $aprrove_upd_res = ($req->approved == 1) ? 0 : 1;
    DB::table('todays')->where('taskId', $req->taskId)->update(['approved' => $aprrove_upd_res]);
    return 203;
});
