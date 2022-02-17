<?php

use App\Http\Controllers\MakePdfController;
use App\Http\Controllers\PersonasController;
use Illuminate\Support\Facades\Route;

use LaravelQRCode\Facades\QRCode;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('persona', PersonasController::class)->middleware('auth');
Route::post('persona/pagar/{id}', [PersonasController::class, 'pagar'])->middleware('auth');

Route::get('get', [MakePdfController::class, 'hospital']);
Route::get('get/certificado', [MakePdfController::class, 'certificado']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
