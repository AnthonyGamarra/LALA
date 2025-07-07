<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CabezaController;
use App\Http\Controllers\CabezaOoccController;

// LOGIN
Route::match(['get', 'post'], '/', [LoginController::class, 'login'])->name('login');

// OODD
Route::match(['get', 'post'], '/ooddselect', [CabezaController::class, 'ooddselect'])->name('ooddselect');
Route::match(['get', 'post'], '/ooddmain', [CabezaController::class, 'ooddmain'])->name('ooddmain');

//Route::get('/ooddselect', [CabezaController::class, 'ooddselect'])->name('ooddselect');
Route::get('/consolida_red', [CabezaController::class, 'consolida_red'])->name('consolida_red');

Route::match(['get', 'post'], '/ooddhoja', [CabezaController::class, 'ooddhoja'])->name('ooddhoja');
Route::post('/grabaooddhoja', [CabezaController::class, 'grabaooddhoja'])->name('grabaooddhoja');
Route::match(['get', 'post'], '/exportaooddhoja', [CabezaController::class, 'exportaooddhoja'])->name('exportaooddhoja');
Route::match(['get', 'post'], '/consolidaeess', [CabezaController::class, 'consolidaeess'])->name('consolidaeess');
Route::match(['get', 'post'], '/consolidared', [CabezaController::class, 'consolidared'])->name('consolidared');
Route::post('/cerrarooddhoja', [CabezaController::class, 'cerrarooddhoja'])->name('cerrarooddhoja');

//Route::match(['get', 'post'], '/grabarooddhoja', [CabezaController::class, 'grabarooddhoja'])->name('grabarooddhoja');

// OOCC
Route::post('/oocc/agregar-actividad', [CabezaOoccController::class, 'agregarActividad'])->name('oocc.agregarActividad');
Route::match(['get', 'post'], '/ooccselect', [CabezaOoccController::class, 'ooccselect'])->name('ooccselect');
Route::match(['get', 'post'], '/ooccmain', [CabezaOoccController::class, 'ooccmain'])->name('ooccmain');
Route::match(['get', 'post'], '/oocchoja', [CabezaOoccController::class, 'oocchoja'])->name('oocchoja');
Route::match(['get', 'post'], '/consolida_oocc', [CabezaOoccController::class, 'consolida_oocc'])->name('consolida_oocc');
Route::match(['get', 'post'], '/consolidadoGeneralOOCC', [CabezaOoccController::class, 'consolidadoGeneralOOCC'])->name('consolidadoGeneralOOCC');
Route::match(['get', 'post'], '/exportaoocchoja', [CabezaOoccController::class, 'exportaoocchoja'])->name('exportaoocchoja');
Route::post('/grabaoocchoja', [CabezaOoccController::class, 'grabaoocchoja'])->name('grabaoocchoja');
Route::post('/oocc/renombrar-actividad', [CabezaOoccController::class, 'renombrarActividad'])->name('oocc.renombrarActividad');
Route::post('/cerrar-oocc', [CabezaOoccController::class, 'cerraroocchoja'])->name('oocc.cerraroocchoja');

// LOGOUT
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');


//MASTER OOCC
Route::match(['get', 'post'], '/ooccmasterselect', [CabezaController::class, 'ooccmasterselect'])->name('ooccmasterselect');
Route::match(['get', 'post'], '/ooccmastermain', [CabezaController::class, 'ooccmastermain'])->name('ooccmastermain');

//MASTER OODD
Route::match(['get', 'post'], '/ooddmasterselect', [CabezaController::class, 'ooddmasterselect'])->name('ooddmasterselect');
Route::match(['get', 'post'], '/ooddmastermain', [CabezaController::class, 'ooddmastermain'])->name('ooddmastermain');


Route::match(['get', 'post'], '/ooddmasterrestaura', [CabezaController::class, 'ooddmasterrestaura'])->name('ooddmasterrestaura');
Route::match(['get', 'post'], '/ooddmastercambia', [CabezaController::class, 'ooddmastercambia'])->name('ooddmastercambia');

Route::match(['get', 'post'], '/ooccmasterrestaura', [CabezaController::class, 'ooccmasterrestaura'])->name('ooccmasterrestaura');
Route::match(['get', 'post'], '/oocmastercambia', [CabezaController::class, 'ooccmastercambia'])->name('ooccmastercambia');