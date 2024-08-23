<?php

namespace App\Functions;

use Carbon\Carbon;
use App\Models\Contrat;
use App\Models\BureauPoste;
use Illuminate\Support\Str;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use App\Procedures\StoredProcedures;
use Filament\Notifications\Notification;

class Functions
{
    const ACTIVATED = 2;

    const REJECTED = 1;


     /** 
     * @function updates record and sets parameters for sms
     */

    public static function setValidationParameters($record)
    {
        
        $contrat = Contrat::find($record->ref_contrat);

      
        $record->update([
            "code_etat_bp" => self::ACTIVATED, //atribuée
        ]);

        $contrat->update([
             "code_etat_contrat" => 0 // encours
        ]);

        
        $refSms = Str::random(10);

        $bureau = BureauPoste::find($record->code_bureau);

        $telephone = 22890110599;    // TODO:  $record->telephone;

        $message = 'NOUS AVONS LE PLAISIR DE VOUS INFORMER QUE LA BOITE POSTALE '.$bureau->code_postal_buro.' BP '.$record->designation_bp.' VOUS EST ATTRIBUEE. RENDEZ VOUS A L\'AGENCE '.$bureau->designation_buro .' POUR SIGNER VOTRE CONTRAT.';

        $dateSms = Carbon::parse(today())->format('d/m/y');

        $origine = '0';
        

        

        if (! is_null($bureau)) {

            $origine = $bureau->libelle_poste;

        } else {
            $origine = $record->code_bureau;
        }

        return [
            'refSms' => $refSms,
            'telephone' => $telephone,
            'message' => $message,
            'dateSms' => $dateSms,
            'origine' => $origine,
            'bureau' => $bureau,

        ];

    }

    /** 
     * @function   //updates record and sets parameters for sms
     */

    public static function setRejectionParameters($record)
    {

   

        $contrat = Contrat::find($record->ref_contrat);

        $record->update([
              "code_etat_bp" => self::REJECTED, //atribuée
        ]);

         $contrat->update([
             "code_etat_contrat" => 2 // bloqué
        ]);

        $refSms = Str::random(10);

        $telephone = 22890110599;  //        // TODO:  $record->telephone;

        $message = 'NOUS AVONS LE REGRET DE VOUS INFORMER QUE VOTRE DEMANDE D\'ABONNEMENT A UNE BOITE POSTALE A ETE REJETEE.';

        $dateSms = Carbon::parse(today())->format('d/m/y');

        $bureau = BureauPoste::find($record->code_bureau);

        if (! is_null($bureau)) {
            $origine = $bureau->libelle_poste;
        } else {
            $origine = $record->code_bureau;
        }

        return [
            'refSms' => $refSms,
            'telephone' => $telephone,
            'message' => $message,
            'dateSms' => $dateSms,
            'origine' => $origine,
            'bureau' => $bureau,

        ];

    }

    /** 
     * @function  // sends validation sms via stored procedure
     */

    public static function sendValidation($record)
    {
       
        $data = Functions::setValidationParameters($record); 

        Notification::make('valide')
            ->body('Boîte postale attribuée')
            ->color(Color::Green)
            ->send();

        return StoredProcedures::sendSms($data['refSms'], $data['telephone'], $data['message'], $data['dateSms'], $data['origine']);
    }


    /** 
     * @function sends rejection sms via stored procedure
     */

    public static function sendRejection($record)
    {
        

        $data = self::setRejectionParameters($record);

        Notification::make('valide')
            ->body('Boîte postale rejetée')
            ->color(Color::Red)
            ->send();

        return StoredProcedures::sendSms($data['refSms'], $data['telephone'], $data['message'], $data['dateSms'], $data['origine']);
    }




    public static function setRecalParam($record)
    {

   

        $refSms = Str::random(10);

        $telephone = 22891568182;  //        // TODO:  $record->telephone;

        $bureau = BureauPoste::where("code_bureau",$record->code_bureau)->first()->designation_buro;

        $message = 'VOUS ETES PIRES DE PASSER A L\'AGENCE '.$bureau.' CORRIGER VOS INFORMATIONS RELATIVES A VOTRE DEMANDE D\'ABONNEMENT A UNE BOITE POSTALE. MERCI';

        $dateSms = Carbon::parse(today())->format('d/m/y');

        $bureau = BureauPoste::find($record->code_bureau);

        if (! is_null($bureau)) {
            $origine = $bureau->libelle_poste;
        } else {
            $origine = $record->code_bureau;
        }

        return [
            'refSms' => $refSms,
            'telephone' => $telephone,
            'message' => $message,
            'dateSms' => $dateSms,
            'origine' => $origine,
            'bureau' => $bureau,

        ];

    }

    /** 
     * @function  // sends RECALL sms via stored procedure
     */

    public static function sendRecallSms($record)
    {
       
        $data = Functions::setRecalParam($record); 

        Notification::make('recall')
            ->body('SMS envoyé!')
            ->color(Color::Blue)
            ->send();

        return StoredProcedures::sendSms($data['refSms'], $data['telephone'], $data['message'], $data['dateSms'], $data['origine']);
    }
}
