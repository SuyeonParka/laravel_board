<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiListController;

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

Route::get('/list/{id}', [ApiListController::class, 'getlist']);
//post로 해서 세그먼트{id}필요 없음
Route::post('/list', [ApiListController::class, 'postlist']);
//update니까 id를 받아야함(form data로 받을수도 있어서 굳이 id를 세그먼트로 안 받아도 됨)
Route::put('/list/{id}', [ApiListController::class, 'putlist']);
Route::delete('/list/{id}', [ApiListController::class, 'deletelist']);