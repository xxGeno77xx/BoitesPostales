<?php

namespace App\Filament\Resources\ContratResource\Pages;

use App\Models\BoitesPostale;
use App\Models\BureauPoste;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ContratResource;

class ListContrats extends ListRecords
{
    protected static string $resource = ContratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $loggedUserCodeBureau = auth()->user()->code_bureau;

        return BoitesPostale::join('boite.etat_bp', 'boite.etat_bp.code_etat_bp', 'boite.boite_postale.code_etat_bp')
        ->join('boite.operation', 'boite.operation.id_bp', 'boite.boite_postale.id_bp')
        ->join('boite.reglement', 'boite.reglement.id_operation', 'boite.operation.id_operation')
        ->join('boite.abonne', 'boite.abonne.id_abonne', 'boite.operation.id_abonne')
        ->leftjoin("categorie_professionnelle", "categorie_professionnelle.code_categ_prof", "boite.abonne.code_categ_prof")
        ->leftjoin("boite.type_piece", "boite.type_piece.code_type_piece", "boite.abonne.code_type_piece")
        ->leftjoin('boite.ville', 'boite.ville.code_ville','boite.abonne.code_ville')
        ->join("boite.contrat", "boite.contrat.id_operation", "reglement.id_operation")
              
        ->selectRaw(
            
               ' 
              
                boite.boite_postale.id_bp,
                boite.boite_postale.code_bureau,
                boite.boite_postale.designation_bp,
                abonne.nom as nom_abonne,
                abonne.tel_mobile as telephone,
                abonne.prenoms as prenom_abonne,
                abonne.raison_sociale,
                reglement.date_reglement,
                boite.reglement.montant_reglement,
                reglement.id_reglement,
                boite.boite_postale.code_etat_bp,
                boite.abonne.document_name,
                boite.abonne.tel_fixe,
                boite.abonne.num_piece,
                boite.abonne.infos_compl,
                boite.abonne.titre,
                boite.abonne.date_deliv_piece,
                boite.abonne.autorite_deliv_piece,
                boite.abonne.tel_mobile,
                boite.abonne.email,
                boite.abonne.nom_maison,
                boite.abonne.num_maison,
                boite.abonne.nom_rue,
                boite.abonne.num_rue,
                boite.abonne.quartier,
                boite.abonne.premier_resp,
                boite.abonne.datenais,
                boite.abonne.num_cpte,
                boite.abonne.utilisateur,
                categorie_professionnelle.libelle_categ_prof,
                boite.ville.libelle_ville,
                boite.abonne.banque,
                boite.abonne.email2,
                boite.type_piece.libelle_piece,
                boite.operation.id_operation,
                contrat.ref_contrat,
                contrat.date_debut_contrat,
                contrat.date_fin_contrat,
                boite.abonne.nationalite
                 
          
                '
        )
        ->whereRaw('boite.boite_postale.code_etat_bp = ?', [6])
        ->orderby("boite.reglement.date_reglement", "desc")
        ->whereNotNull("boite.abonne.document_name");;
        

    }
}
