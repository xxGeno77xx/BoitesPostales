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
    const VALIDE = "DEMANDE VALIDE";
    const REVOIR = "DEMANDE A REVOIR";

    public static function sendInfosBackToFront($record)
    {
        // $status = "" ;

        // $endpoint = '';

        // if($record->code_etat_bp == 2)
        // {
        //     $status = Self::VALIDE;
        // }

        // else $status = Self::REVOIR;
  
        // $response = Http::withHeaders([

        //     'Authorization' => env('API_KEY', null),
            
        // ])->get($endpoint, [
            
        //     'status' => $status,
        // ]);

        // $result = $response->collect()["content"];
 
    }
}
