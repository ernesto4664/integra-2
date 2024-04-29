<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('test', 'ServiceController@test');

Route::get('file/open/pdf/{file}', 'ServiceController@fileOpenPdf');

Route::get('notificate', 'UserController@notificate');

Route::get('cambiar/contrasena/user/{id}', 'UserController@formRecoveryPassword');

Route::get('encuesta-01', function () {
    return view('pages.survey');
});

Route::get('cache', function () {
    \Cache::flush();
});

Route::get('service/current/settlement', 'ServiceController@currentSettlement');

Route::get('certificate', 'CertificateController@test');