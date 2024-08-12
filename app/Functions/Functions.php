<?php

namespace App\Functions;

use App\Models\BureauPoste;
use App\Procedures\StoredProcedures;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Illuminate\Support\Str;

class Functions
{
    const ACTIVATED = 2;

    const REJECTED = 7;


     /** 
     * @function updates record and sets parameters for sms
     */

    public static function setValidationParameters($record)
    {
        $record->update([
            "code_etat_bp" => self::ACTIVATED //atribuée
        ]);

        $refSms = Str::random(10);

        $telephone = 22890658724;  //record->telephone

        $message = 'Mr '.strtoupper($record->nom_abonne).' '.strtoupper($record->prenom_abonne).', NOUS AVONS LE PLAISIR DE VOUS ANNONCER QUE LA BOITE POSTALE NUMERO '.$record->designation_bp.' VOUS A ETE ATTRIBUEE.';

        $dateSms = Carbon::parse(today())->format('d/m/y');

        $origine = '0';

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
     * @function   //updates record and sets parameters for sms
     */

    public static function setRejectionParameters($record)
    {
       

        $record->update([
            "code_etat_bp" => self::REJECTED //atribuée
        ]);

        $refSms = Str::random(10);

        $telephone = 22890658724;  //$record->telephone;

        $message = 'Mr '.strtoupper($record->nom_abonne).' '.strtoupper($record->prenom_abonne).', NOUS AVONS LE REGRET DE VOUS ANNONCER QUE VOTRE DEMANDE DE BOITE POSTALE A ETE REJETEE.';

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
}
