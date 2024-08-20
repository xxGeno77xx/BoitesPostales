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


    public static function getTarifs($codesousgpe, $idservice, $idregroup, $idparam_facturation, $dates,  $au, $code_bureau,  $duree,  $code_type_op,  $soumis_tva ): array
    {

        try {

            $pdo = DB::getPdo();

            $procedure = $pdo->prepare("begin procedures.tarif_abonnement_boite(
                :codesousgpe,
                :idservice,
                :idregroup,
                :idparam_facturation,
                :dates,
                :au,
                :code_bureau,
                :duree,
                :code_type_op,
                :soumis_tva,
                :redevance_bp,
                :penalite,
                :taxe_fixe,
                :tva,
                :redevance,
                :an_bonus); end;");

            // $code = str_repeat(' ', 10);  // Pré-allocation de l'espace pour les variables OUT
            // $info = str_repeat(' ', 100);

            $procedure->bindParam(':codesousgpe', $codesousgpe, PDO::PARAM_INT);
            $procedure->bindParam(':idservice', $idservice, PDO::PARAM_INT);
            $procedure->bindParam(':idregroup', $idregroup, PDO::PARAM_INT);
            $procedure->bindParam(':idparam_facturation', $idparam_facturation, PDO::PARAM_INT);
            $procedure->bindParam(':dates', $dates, PDO::PARAM_STR);
            $procedure->bindParam(':au', $au, PDO::PARAM_STR);
            $procedure->bindParam(':code_bureau', $code_bureau, PDO::PARAM_INT);
            $procedure->bindParam(':duree', $duree, PDO::PARAM_INT);
            $procedure->bindParam(':code_type_op', $code_type_op, PDO::PARAM_INT);
            $procedure->bindParam(':soumis_tva', $soumis_tva, PDO::PARAM_INT);
            $procedure->bindParam(':redevance_bp', $redevance_bp, PDO::PARAM_INT);
            $procedure->bindParam(':penalite', $penalite, PDO::PARAM_INT);
            $procedure->bindParam(':taxe_fixe', $taxe_fixe, PDO::PARAM_INT);
            $procedure->bindParam(':tva', $tva, PDO::PARAM_INT);
            $procedure->bindParam(':redevance', $redevance, PDO::PARAM_INT);
            $procedure->bindParam(':an_bonus', $an_bonus, PDO::PARAM_INT);

            $procedure->execute();

        } catch (\Exception $e) {

            Notification::make('error')
                ->color(Color::Red)
                ->body("Erreur lors du calcul de la tarification")
                ->send();
        }

        return [

                "redevance_bp" => $redevance_bp,
                "penalite" => $penalite,
                "taxe_fixe" => $taxe_fixe,
                "tva" => $tva,
                "redevance" => $redevance,
                "an_bonus" => $an_bonus
        
        ];
    }
}
