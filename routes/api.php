<?php

use Illuminate\Http\Request;

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

Route::post('/imageUpload','ImageController@ImageUpload')->name('image-upload');
Route::post('/uploadToS3','ImageController@UploadToS3')->name('image-upload');
Route::get('/getResize','ImageController@GetResizedImage')->name('image-resize');