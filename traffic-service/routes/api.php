<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoadreportController;
use App\Http\Controllers\RoadreportController_v2;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
| API Routes
|---------------------------------------------------------------------------
|
| Ici, vous pouvez enregistrer les routes API pour votre application. Ces
| routes sont chargées par le RouteServiceProvider dans un groupe qui
| est assigné au groupe middleware "api". Amusez-vous à construire votre API !
|
*/

Route::get('/', function(){
    return  response()->json(['status' => 'ok' , 'service' => 'traffic' ]);
});
Route::get('/road-issue-types', [RoadreportController::class, 'getTypeOfRepport']);
Route::get('/road-issues', [RoadreportController::class, 'getRepports']);
Route::resource('road-issues', RoadreportController::class)->except([ 'getTypeOfRepport', 'getRepports']);

Route::middleware(['microauth'])->group(function () {
  

});

Route::prefix('v2')->middleware(['microauth'])->group(function () {
    Route::resource('road-issues', RoadreportController_v2::class)->only([ 'index','show','store','update']);


});