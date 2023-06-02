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
//post로 db에 값을 넣는거니까 세그먼트 파라미터로 db값을 불러올 필요가 없어서 파라미터 안적어줌
Route::post('/list', [ApiListController::class, 'postlist']);
//update니까 id를 받아야함(form data로 받을수도 있어서 굳이 id를 세그먼트로 안 받아도 됨)
//form태그는 body안에 담겨있음
Route::put('/list/{id}', [ApiListController::class, 'putlist']);
Route::delete('/list/{id}', [ApiListController::class, 'deletelist']);