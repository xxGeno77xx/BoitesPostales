<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Functions\RenouvellementNotif;

class CheckRenouvellements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkRenewals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $renouvellements = DB::table("boite.operation")
            ->join("gateway.paiement", "gateway.paiement.numero_operation", "boite.operation.id_operation")
            ->join("boite.notification", "boite.notification.id_paiement", "gateway.paiement.idpaiement")
            ->join("boite.boite_postale", "boite.boite_postale.id_bp", "boite.operation.id_bp")
            ->join("boite.contrat", function($join){

                $join->on("boite.contrat.id_abonne", "boite.operation.id_abonne")
                ->whereColumn("boite.operation.id_abonne", "boite.contrat.id_abonne");
                // ->whereColumn("boite.operation.id_abonne", "boite.contrat.id_abonne");
            })
            ->join("boite.bureau", "boite.bureau.code_bureau", "boite.notification.code_bureau")
            ->where("code_type_op", 3) // renouvellements
            ->whereNull("title")
            ->select("boite.notification.id_notif", "boite.contrat.date_fin_contrat", "boite.boite_postale.designation_bp", "boite.bureau.code_postal_buro")->get();
     
        if ($renouvellements->count() > 0) {

            foreach ($renouvellements as $renouvellement) {
              
                $message = "BONJOUR, VOTRE REDEVANCE DE BOITE POSTALE ".$renouvellement->code_postal_buro."BP".$renouvellement->designation_bp." A ETE REGLEE. LA NOUVELLE DATE DE FIN DE CONTRAT EST LE ".Carbon::parse($renouvellement->date_fin_contrat)->format("d/m/Y");

                RenouvellementNotif::sendInfosToFront($renouvellement->id_notif, $message);

                $this->info('The command was successful! Renewal Status updated');
            }
        }
        else  $this->info('All clear, nothing to update');

    }
}
