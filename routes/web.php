<?php

use App\Http\Controllers\MakePdfController;
use App\Http\Controllers\PersonasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('get/denuncia', [MakePdfController::class, 'denuncia']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/cedula/{nro}', function ($nro) {
    dd($nro);
});


Route::get('/scrapping/{nro}', function ($nro) {
    $crawler = Goutte::request('GET', 'http://www.cne.gob.ve/web/registro_electoral/ce_rr2022.php?nacionalidad=V&cedula=' . $nro);
    $scp =  $crawler->filter('table >  tr > td > table > tr > td > table > tr')->each(function ($node) {
        return $node->text();
        // array_push($data, $node->text());
    });

    $fullname = str_replace('Nombre: ', '', $scp[5]);

    $arr_name = explode(' ', $fullname);

    if (count($arr_name) === 3) {
        $res = [
            'nombres' => $arr_name[0] . ' ' . $arr_name[1],
            'apellidos' => $arr_name[2]
        ];
        return response()->json($res);
    }

    if (count($arr_name) === 4) {
        $res = [
            'nombres' => $arr_name[0] . ' ' . $arr_name[1],
            'apellidos' => $arr_name[2] . ' ' . $arr_name[3]
        ];
        return response()->json($res);
    }


    if (count($arr_name) === 5) {
        $res = [
            'nombres' => $arr_name[0] . ' ' . $arr_name[1],
            'apellidos' => $arr_name[2] . ' ' . $arr_name[3] . ' ' . $arr_name[4]
        ];
        return response()->json($res);
    }


    if (count($arr_name) === 6) {
        $res = [
            'nombres' => $arr_name[0] . ' ' . $arr_name[1],
            'apellidos' => $arr_name[2] . ' ' . $arr_name[3] . ' ' . $arr_name[4] . ' ' . $arr_name[5]
        ];
        return response()->json($res);
    }





    // dd($fullname, $arr_name);
});
