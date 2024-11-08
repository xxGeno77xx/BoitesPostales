<?php

use Carbon\Carbon;
use App\Functions\Atd;
use App\Models\BoitesPostale;
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

 $title = "RENOUVELLEMENT ABONNEMENT";

 
 $renouvellements = DB::table("boite.operation")
 ->join("gateway.paiement", "gateway.paiement.numero_operation", "boite.operation.id_operation" )
 ->join("boite.notification", "boite.notification.id_paiement", "gateway.paiement.idpaiement")
 ->where("code_type_op", 3) // renouvellements
 ->pluck("id_notif");

 
 

});

Route::get('/{record}/pdf', [DownloadController::class, 'downloadPdf'])->name('contrat.pdf.ddl');

