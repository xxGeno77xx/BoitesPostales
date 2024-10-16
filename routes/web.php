<?php

use Carbon\Carbon;
use App\Procedures\StoredProcedures;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadController;

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

Route::get('/test', function () {
 
    return  config("app.tarifAbonnementUrl");
});

Route::get('/{record}/pdf', [DownloadController::class, 'downloadPdf'])->name('contrat.pdf.ddl');

