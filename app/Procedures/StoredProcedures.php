<?php

namespace App\Procedures;

use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use PDO;

class StoredProcedures
{
    public static function sendSms($refSms, $telephone, $message, $dateSms, $origine): array
    {

        try {
            $pdo = DB::getPdo();

            $procedure = $pdo->prepare('begin imobile.sendsms(:refSms,
                :telephone,
                :message,
                :dateSms,
                :origine,
                :code,
                :info); end;');

            $code = str_repeat(' ', 10);  // Pré-allocation de l'espace pour les variables OUT
            $info = str_repeat(' ', 100);

            $procedure->bindParam(':refSms', $refSms, PDO::PARAM_STR);
            $procedure->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $procedure->bindParam(':message', $message, PDO::PARAM_STR);
            $procedure->bindParam(':dateSms', $dateSms, PDO::PARAM_STR);
            $procedure->bindParam(':origine', $origine, PDO::PARAM_STR);
            $procedure->bindParam(':code', $code, PDO::PARAM_STR);
            $procedure->bindParam(':info', $info, PDO::PARAM_STR);

            $procedure->execute();

        } catch (\Exception $e) {

            Notification::make('error')
                ->color(Color::Red)
                ->body("Erreur lors de l'envoi du SMS")
                ->send();
        }

        return [$code, $info];
    }
}
