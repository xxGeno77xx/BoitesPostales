<?php

namespace App\Filament\Resources\BoitesPostaleResource\Pages;

use Filament\Actions;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BoitesPostaleResource;

class ListBoitesPostales extends ListRecords
{
    protected static string $resource = BoitesPostaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),


        ];
    }

    protected function getTableQuery(): ?Builder
    {

        return static::getResource()::getEloquentQuery()
            ->join("boite.etat_bp", "boite.etat_bp.code_etat_bp", "boite.boite_postale.code_etat_bp")
            ->join("boite.contrat", "boite.contrat.id_bp", "boite.boite_postale.id_bp")
            ->join("boite.abonne", "boite.abonne.id_abonne", "boite.contrat.id_abonne")
            ->join("boite.reglement", "boite.reglement.id_operation", "boite.contrat.id_operation")
            ->selectRaw(

                "
                boite.boite_postale.id_bp,
                boite.boite_postale.code_bureau,
                boite.boite_postale.designation_bp,
                boite.contrat.date_fin_contrat as fin_contrat,
                boite.contrat.date_debut_contrat as debut_contrat,
                abonne.nom as nom_abonne,
                abonne.tel_mobile as telephone,
                abonne.prenoms as prenom_abonne,
                abonne.raison_sociale,
                reglement.date_reglement,
                boite.reglement.montant_reglement,
                reglement.id_reglement,
                boite.boite_postale.code_etat_bp,
                boite.abonne.document_name
                ",

            )
            ->whereRaw("boite.boite_postale.code_etat_bp = ?", [6]);
    }
}
