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

    public static function setValidationParameters($record)
    {
        //updates record and sets parameters for sms
        // ------------------------------------------

        // $record->update([
        //     "code_etat_bp" => self::ACTIVATED //atribuée
        // ]);

        $refSms = Str::random(10);

        $telephone = 22891568182;  //record->telephone

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

    public static function setRejectionParameters($record)
    {
        //updates record and sets parameters for sms
        // ------------------------------------------

        // $record->update([
        //     "code_etat_bp" => self::REJECTED //atribuée
        // ]);

        $refSms = Str::random(10);

        $telephone = 22891568182;  //$record->telephone;

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

    public static function sendValidation($record)
    {
        // sends validation sms via stored procedure

        $data = Functions::setValidationParameters($record);

        Notification::make('valide')
            ->body('Boîte postale attribuée')
            ->color(Color::Green)
            ->send();

        return StoredProcedures::sendSms($data['refSms'], $data['telephone'], $data['message'], $data['dateSms'], $data['origine']);
    }

    public static function sendRejection($record)
    {
        // sends rejection sms via stored procedure

        $data = self::setRejectionParameters($record);

        Notification::make('valide')
            ->body('Boîte postale attribuée')
            ->color(Color::Red)
            ->send();

        return StoredProcedures::sendSms($data['refSms'], $data['telephone'], $data['message'], $data['dateSms'], $data['origine']);
    }
}
