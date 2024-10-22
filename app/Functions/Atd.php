<?php

namespace App\Functions;

use Carbon\Carbon;
use App\Models\Contrat;
use App\Models\BureauPoste;
use Illuminate\Support\Str;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use App\Procedures\StoredProcedures;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class Atd
{
    const VALIDE = "DEMANDE VALIDEE";
    const REVOIR = "DEMANDE A CORRIGER";

    const TITLE = "VALIDATION ABONNEMENT";

    public static function sendInfosBackToFront($record, $status)
    {

        $infos = DB::table("boite.notification")
            ->join("gateway.paiement", "gateway.paiement.idpaiement", "=", "boite.notification.id_paiement")
            ->where("gateway.paiement.numero_operation", "=", $record->id_operation)
            ->first();

        $notificationID = $infos->id_notif;

        $notification = DB::table("boite.notification")->where("id_notif", "=", $notificationID);

        try {

            //$notification = DB::table("boite.notification")->where("id_operation", $record->id_operation)->first();

            $endpoint = config("app.callbackApiUrl", " ");

            if ($notification) {

                $response = Http::post($endpoint, [

                    'idNotif' => $notificationID,
                    'title' => self::TITLE,
                    'message' => $status

                ]);

                if ($response->collect("success")[0] == false) {
                    Notification::make('error')
                        ->title("Erreur")
                        ->body('Erreur lors de la mise à jour du statut de la demande sur la plateforme')
                        ->color(Color::Red)
                        ->send();
                } else {
                    Notification::make('success')
                        ->title("Succes")
                        ->body('Mise à jour du statut de la demande sur la plateforme effectué.')
                        ->color(Color::Green)
                        ->send();
                }
            }


        } catch (\Exception $e) {

            Notification::make('error')
                ->title("Erreur")
                ->body('Erreur lors de l\'appel à l\'api')
                ->color(Color::Red)
                ->send();
        }

    }
}
