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
 
    $endpoint = config("app.callbackApiUrl", " ");

    $response = Http::post($endpoint, [
    
        'idNotif' => 43,
        'title' => 'TITLE',
        'message' => 'message'

    ]);

   

    if($response->collect("success")[0] == false)
    {
        dd("false");
    }

    dd('true');
    // dd($response->body());
});

Route::get('/{record}/pdf', [DownloadController::class, 'downloadPdf'])->name('contrat.pdf.ddl');

