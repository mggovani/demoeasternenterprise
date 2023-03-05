<?php

use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\UserDataImportController;
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

Route::group(['prefix' => 'v1'], function () {
  	Route::get('import-data', [UserDataImportController::class, 'importData']);
	Route::get('companies', [CompanyController::class, 'index']);
});
