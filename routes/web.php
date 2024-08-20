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


    // $result = StoredProcedures::getTarifs(1,1,1,4, Carbon::parse('01/01/2024'), Carbon::parse('01/01/2029'), 963,2,5,1);
   

    $e = DB::select("select sysdate from dual");

    $sysdate = $e[0]->sysdate;
 
 
    $result = StoredProcedures::getTarifs(1,1,1,1 , Carbon::parse($sysdate)->format("Y/m/d"), Carbon::parse('01/01/29')->format("Y/m/d"), 625,5,5,1);

    dd($result);

    return $result;
});

Route::get('/{record}/pdf', [DownloadController::class, 'downloadPdf'])->name('contrat.pdf.ddl');

