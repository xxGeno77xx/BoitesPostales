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

class RenouvellementNotif
{
    const TITLE = "RENOUVELLEMENT BOITE POSTALE";

    public static function sendInfosToFront(int $notificationID, $message )
    {
        
        $endpoint = config("app.callbackApiUrl", " ");

        $response = Http::post($endpoint, [

            'idNotif' => $notificationID,
            'title' => self::TITLE,
            'message' => $message

        ]);

        if ($response->collect("success")[0] == false) {

            throw (new \Exception('Erreur lors de la mise à jour du statut de la demande sur la plateforme'));

        }

        if ($response->collect("message")[0] == "Couldn't find a task matching the values you provided.") {

            throw (new \Exception('Cette notif a déjà été mise à jour.'));

        }

    }
}