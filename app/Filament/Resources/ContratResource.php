<?php

namespace App\Filament\Resources;

use Schema;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Ville;
use App\Models\Abonne;
use App\Models\Contrat;
use Filament\Forms\Form;
use App\Enums\RolesEnums;
use App\Models\TypePiece;
use Filament\Tables\Table;
use App\Models\BureauPoste;
use App\Functions\Functions;
use App\Models\CategoriePro;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use App\Forms\Components\CfeField;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use App\Procedures\StoredProcedures;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Forms\Components\IdentityViewer;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\ContratResource\Pages;

class ContratResource extends Resource
{
    protected static ?string $model = Contrat::class;

    protected static ?string $label = 'Validation des contrats';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make("Pieces justificatives")
                    ->columnSpanFull()
                    ->schema([

                        IdentityViewer::make("Id")
                            ->label("Pièce d'identité"),

                        CfeField::make("cfe")
                            ->label("Carte CFE")
                            ->visible(fn($record) => $record->cfe_document_name == null? false : true), // $recod->cfe_document_name == null? false : true

                    ]),

                Fieldset::make("Informations de l'abonné")

                    ->schema([

                        Grid::make(3)
                            ->schema([

                                TextInput::make('nom_abonne')
                                    ->label('Nom abonné')
                                    ->placeholder('-'),

                                TextInput::make('prenom_abonne')
                                    ->label('Prénom abonné')
                                    ->placeholder('-'),


                                TextInput::make('telephone')
                                    ->label('telephone')
                                    ->placeholder('-')
                                    ->numeric(),

                                TextInput::make('tel_fixe')
                                    ->label('Téléphone fixe')
                                    ->placeholder('-')
                                    ->numeric(),

                                TextInput::make('num_piece')
                                    ->label('Numéro de la pièce')
                                    ->placeholder('-'),


                                TextInput::make('titre')
                                    ->label('Titre')
                                    ->placeholder('-'),

                                DatePicker::make('date_deliv_piece')
                                    ->label('Date de délivrance de la pièce')
                                    ->displayFormat('d/m/Y')
                                    ->placeholder('-'),

                                TextInput::make('autorite_deliv_piece')
                                    ->label('Autorité délivrant la pièce')
                                    ->placeholder('-'),

                                TextInput::make('tel_mobile')
                                    ->label('Téléphone')
                                    ->placeholder('-')
                                    ->numeric(),

                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->placeholder('-'),


                                TextInput::make('nom_maison')
                                    ->label('Maison')
                                    ->placeholder('-'),

                                TextInput::make('raison_sociale') //ici
                                    ->label('Raison sociale')
                                    ->placeholder('-'),


                                TextInput::make('num_maison')
                                    ->label('Maison N°')
                                    ->placeholder('-'),

                                TextInput::make('nom_rue')
                                    ->label('Rue')
                                    ->placeholder('-'),

                                TextInput::make('num_rue')
                                    ->label('Rue N°')
                                    ->placeholder('-'),

                                TextInput::make('quartier')
                                    ->label('Quartier')
                                    ->placeholder('-'),

                                TextInput::make('premier_resp')
                                    ->label('Premier responsable')
                                    ->placeholder('-'),

                                DatePicker::make('datenais')
                                    ->label('Date de naissance')
                                    ->displayFormat('d/m/Y')
                                    ->placeholder('-'),

                                TextInput::make('num_cpte')
                                    ->label('Numéro de compte')
                                    ->placeholder('-')
                                    ->numeric(),

                                Select::make('code_categ_prof')
                                    ->label('Catégorie professionnelle')
                                    ->searchable()
                                    ->options(CategoriePro::pluck("libelle_categ_prof", "code_categ_prof")),

                                Select::make('code_ville')
                                    ->label('Ville')
                                    ->options(Ville::pluck("libelle_ville", "code_ville"))
                                    ->searchable(),

                                TextInput::make('banque')
                                    ->label('banque')
                                    ->placeholder('-'),

                                TextInput::make('email2')
                                    ->label('email2')
                                    ->email()
                                    ->placeholder('-'),

                                Select::make('code_type_piece')
                                    ->label('Pièce d\'identité')
                                    ->options(TypePiece::pluck("libelle_piece", "code_type_piece"))
                                    ->searchable(),

                                TextInput::make('nationalite')
                                    ->label('Nationalité')
                                    ->placeholder('-'),

                                TextInput::make('infos_compl')
                                    ->label('Informations complémentaires')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ]),

                    ]),

                Fieldset::make('Règlement')
                    ->disabledOn("edit")
                    ->schema([

                        Grid::make(3)
                            ->schema([
                                TextInput::make('id_reglement')
                                    ->label('ID règlement')
                                    ->placeholder('-'),

                                DatePicker::make('date_reglement')
                                    ->label('Date du règlement')
                                    ->format('d/m/Y'),

                                Select::make('code_bureau')
                                    ->label('Bureau de poste')
                                    ->options(BureauPoste::pluck("designation_buro", "code_bureau")),

                                TextInput::make('designation_bp')
                                    ->label('Désignation boîte'),

                                DatePicker::make('date_debut_contrat')
                                    ->label('Début du contrat')
                                    ->format('d/m/Y'),

                                DatePicker::make('date_fin_contrat')
                                    ->label('Fin du contrat')
                                    ->format('d/m/Y'),

                                  Grid::make(2)
                                    ->schema([
                                        TextInput::make('id_operation')
                                        ->label('Id de l\'opération'),
    
                                    TextInput::make('montant_reglement')
                                        ->label('Montant payé'),
                                    ]),

                                    TextInput::make('ref_contrat')
                                    ->label('Réference du contrat')
                                    ->placeholder('-'),
                            ]),


                    ]),

                    Hidden::make("catPro")
                        ->default(1),

                Section::make("Tarification")
                    ->schema([

                        Radio::make('type_op')
                        ->label("Type de personne")
                        ->options([
                            5 => 'Personne physique',
                            6 => 'Personne morale',

                        ])
                        ->live()
                        ->afterStateUpdated(function(Callable $get, Callable $set){

                            if($get("type_op") == 5)
                            {
                                $set("catPro", 1) ;// physiqye
                            }
                            else $set("catPro", 2); // morale

                       
                      
                        })
                        ->dehydrated(false)
                        ->inline(),

                        Grid::make(2)
                            ->schema([

                                Grid::make(2)
                                    ->schema([

                                        Select::make('categ_pro')
                                        ->label('Catégorie professionnelle')
                                        ->searchable()
                                        ->dehydrated(false)
                                        ->options(fn( Callable $get) => CategoriePro::where("code_categorie", $get("catPro"))->pluck("libelle_categ_prof", "code_categ_prof")),
    
                                        Select::make('bureau')
                                        ->label('Bureau de poste')
                                        ->searchable()
                                        ->options(BureauPoste::pluck("designation_buro", "code_bureau")),
    
                                    ]),
                              

                                Radio::make('zone')
                                    ->label("Choix de la zone")
                                    ->options([
                                        1 => 'Lomé',
                                        2 => 'Intérieur',

                                    ])
                                    ->dehydrated(false)
                                    ->inline(),

                                Radio::make('duree_abonnement')
                                    ->label("Durée")
                                    ->options([
                                        2 => '2 ans',
                                        5 => '5 ans',

                                    ])
                                    ->inline()
                                    ->live()
                                    ->dehydrated(false)
                                    ->afterStateUpdated(function ($set, $get, $record) {

                                        // $codeSousGroupe = DB::table("boite.categorie_professionnelle")->whereRaw("code_categ_prof = ?", $get("categ_pro"))->first()->code_sous_gpe;

                                        // $idService = 1; // figé
                            
                                        // $idRegroup = $get("zone");

                                        // $idParamFacturation = 4;  //figé comme dans l'api
                            
                                        $code_bureau = $get("bureau");

                                        // $dates = Carbon::parse($record->date_debut_contrat)->format("Y/m/d");

                                        // $au = Carbon::parse($record->date_fin_contrat)->format("Y/m/d");

                                        $duree = $get("duree_abonnement");

                                        $codeTypeOperation = $get("type_op");  // DB::table("boite.operation")->whereRaw("id_operation = ?", [$record->id_operation])->first()->code_type_op;
                            
                                        // $soumisTva = DB::table("boite.categorie_professionnelle")->whereRaw("code_categ_prof = ?", $get("categ_pro"))->first()->soumis_tva;

                                        // $result = StoredProcedures::getTarifs($codeSousGroupe , $idService,$idRegroup, $idParamFacturation, $dates, $au, $code_bureau, $duree, $codeTypeOperation, $soumisTva);
                            
                                       // $result = StoredProcedures::getTarifs($codeSousGroupe, $idService, $idRegroup, $idParamFacturation, $dates, $au, $code_bureau, $duree, 5, $soumisTva);


                                        // $set("redevancebp", $result["redevance_bp"]);
                                        // $set("penalite", $result["penalite"]);
                                        // $set("taxe_fixe", $result["taxe_fixe"]);
                                        // $set("tva", $result["tva"]);
                                        // $set("redevance", $result["redevance"]);
                                        // $set("an_bonus", $result["an_bonus"]);

                                        $response = Http::withHeaders([
                                            'Authorization' => env('API_KEY', null),
                                            
                                        ])->get('http://192.168.60.43:8080/boitepostale-api/boitemanagement/v1/tarifAbonnement', [
                                            
                                            'codeBureau' => intval($code_bureau),
                                            'duree' => intval($duree),
                                            'codeTypeOp' => intval($codeTypeOperation),
                                            'codeCategProf' => intval($get("categ_pro")),
                                        ]);

                                        $result = $response->collect()["content"];

                                        $set("redevancebp", $result["redevanceBp"]);
                                        $set("penalite", $result["penalite"]);
                                        $set("taxe_fixe", $result["taxeFixe"]);
                                        $set("tva", $result["tva"]);
                                        $set("redevance", $result["redevance"]);
                                        $set("an_bonus", $result["anBonus"]);

                                    }),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make("redevancebp")
                                    ->label("Montant à payer")
                                    ->dehydrated(false)
                                    ->disabled(),

                                TextInput::make("penalite")
                                    ->label("Pénalité")
                                    ->dehydrated(false)
                                    ->disabled(),

                                TextInput::make("taxe_fixe")
                                    ->label("Taxe fixe")
                                    ->dehydrated(false)
                                    ->disabled(),

                                TextInput::make("tva")
                                    ->label("TVA")
                                    ->dehydrated(false)
                                    ->disabled(),

                                TextInput::make("redevance")
                                    ->label("redevance")
                                    ->dehydrated(false)
                                    ->disabled(),

                                TextInput::make("an_bonus")
                                    ->label("Années bonus")
                                    ->dehydrated(false)
                                    ->disabled(),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('ref_contrat')
                    ->label('Référence du contrat')
                    ->placeholder('-'),

                // TextColumn::make("id_bp")
                //     ->label("ID de la boîte")
                //     ->placeholder("-")
                //     ->searchable(query: function (Builder $query, string $search): Builder {

                //         return $query->selectRaw('boite.boite_postale.id_bp')
                //             ->whereRaw('LOWER(boite.boite_postale.id_bp) LIKE ?', ['%' . strtolower($search) . '%']);

                //     }),


                BadgeColumn::make("libelle_etat_contrat")
                    ->label("Statut du contrat")
                    ->color(function ($state) {
                        $return = null;
                        switch ($state) {
                            case "CONTRAT EN COURS":
                                $return = Color::Green;
                                break;

                            case "CONTRAT RESILIE":
                                $return = Color::Orange;
                                break;

                            case "CONTRAT BLOQUE":
                                $return = Color::Red;
                                break;

                            case "CONTRAT INITIE":
                                $return = Color::Blue;
                                break;
                        }

                        return $return;
                    }),


                TextColumn::make('nom_abonne')
                    ->label('Nom abonné')
                    ->placeholder('-')
                    ->searchable(query: function (Builder $query, string $search): Builder {

                        return $query->selectRaw('nom')->whereRaw('LOWER(nom) LIKE ?', ['%' . strtolower($search) . '%']);

                    }),

                TextColumn::make('prenom_abonne')
                    ->label('Prénom abonné')
                    ->placeholder('-')
                    ->searchable(query: function (Builder $query, string $search): Builder {

                        return $query->selectRaw('prenoms')->whereRaw('LOWER(prenoms) LIKE ?', ['%' . strtolower($search) . '%']);

                    }),

                TextColumn::make('raison_sociale')
                    ->label('Raison sociale')
                    ->placeholder('-'),

                // TextColumn::make('telephone')
                //     ->label('Téléphone')
                //     ->badge()
                //     ->placeholder('-'),

                TextColumn::make('date_reglement')
                    ->label('Date de règlement')
                    ->badge()
                    ->color(Color::Blue)
                    ->date('d/m/y')
                    ->placeholder('-'),

                // TextColumn::make('montant_reglement')
                //     ->label('Montant'),

                // TextColumn::make('date_debut_contrat')
                //     ->label('Début du contrat')
                //     ->badge()
                //     ->color(Color::Green)
                //     ->date('d/m/Y'),

                // TextColumn::make('date_fin_contrat')
                //     ->label('Fin du contrat')
                //     ->badge()
                //     ->color(Color::Red)
                //     ->date('d/m/Y'),

                TextColumn::make('libelle_sous_gpe')
                ->label('Sous-groupe'),

                TextColumn::make('code_bureau')
                    ->label('Bureau de poste')
                    ->formatStateUsing(function ($state) {


                        $bureauPoste = DB::table("boite.bureau")->whereRaw("code_bureau = ?", [$state])->first();

                        $libellePoste = $bureauPoste ? $bureauPoste->designation_buro : $state;

                        return $libellePoste;
                    })
                    ->placeholder('-'),

                TextColumn::make('duree')
                    ->label('Durée')
                    ->badge()
                    ->color(Color::Blue)
                    ->placeholder('-'),

                // TextColumn::make('id_operation')
                // ->label('operation')
                // ->badge()
                // ->color(Color::Blue)
                // ->placeholder('-'),

            ])
            ->filters([


                Filter::make('date_reglement')
                    ->label('Date du règlement')
                    ->form([

                        Fieldset::make("date_reglement")
                            ->label("Date du règlement")
                            ->schema([
                                Grid::make(2)
                                    ->schema([

                                        DatePicker::make('date_from')
                                            ->label('Du'),

                                        DatePicker::make('date_to')
                                            ->label('Au'),

                                    ])->columns(1),
                            ]),

                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date_reglement', '>=', $date),
                            )
                            ->when(
                                $data['date_to'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date_reglement', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (($data['date_from']) && ($data['date_from'])) {
                            return 'Date du règlement:  ' . Carbon::parse($data['date_from'])->format('d-m-Y') . ' au ' . Carbon::parse($data['date_to'])->format('d-m-Y');
                        }

                        return null;
                    }),

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn() => auth()->user()->hasRole([RolesEnums::Admin()->value, RolesEnums::Dcm()->value]))
                    ->using(function (Model $record, array $data): Model {

                        $abonne = Abonne::find($record->id_abonne);

                        $abonne->update([
                            "nom" => $data["nom_abonne"],
                            "prenoms" => $data["prenom_abonne"],
                            "raison_sociale" => $data["raison_sociale"],
                            "nationalite" => $data["nationalite"],
                            "tel_fixe" => $data["tel_fixe"],
                            "num_piece" => $data["num_piece"],
                            "infos_compl" => $data["infos_compl"],
                            "titre" => $data["titre"],
                            "date_deliv_piece" => $data["date_deliv_piece"],
                            "autorite_deliv_piece" => $data["autorite_deliv_piece"],
                            "tel_mobile" => $data["tel_mobile"],
                            "email" => $data["email"],
                            "nom_maison" => $data["nom_maison"],
                            "num_maison" => $data["num_maison"],
                            "nom_rue" => $data["nom_rue"],
                            "num_rue" => $data["num_rue"],
                            "quartier" => $data["quartier"],
                            "premier_resp" => $data["premier_resp"],
                            "datenais" => $data["datenais"],
                            "num_cpte" => $data["num_cpte"],
                            "code_categ_prof" => $data["code_categ_prof"],
                            "code_type_piece" => $data["code_type_piece"],
                            "code_ville" => $data["code_ville"],
                            "banque" => $data["banque"],
                            "email2" => $data["email2"],


                        ]);

                        return $record;
                    }),

                Tables\Actions\ViewAction::make()
                    ->extraModalFooterActions([
                        Action::make('valider')
                            ->icon('heroicon-o-check-circle')
                            ->requiresConfirmation()
                            ->modalHeading('Valider la demande?')
                            ->modalDescription("En faisant celà, vous validez la demande d'abonnement à la boîte postale vous invitez le demandeur par SMS à venir signer son contrat.")
                            ->color(Color::Green)
                            ->visible(fn() => auth()->user()->hasRole([RolesEnums::Admin()->value, RolesEnums::Dcm()->value]))
                            ->action(function ($record) {

                                Functions::sendValidation($record);

                            }),

                        Action::make('rejeter')
                            ->requiresConfirmation()
                            ->modalHeading('Rejeter la demande?')
                            ->modalDescription("En faisant celà, vous rejetez la demande d'abonnement à cette boîte postale. Le demandeur sera informé par SMS du rejet de sa demande.")
                            ->icon('heroicon-o-x-circle')
                            ->color(Color::Red)
                            ->visible(fn() => auth()->user()->hasRole([RolesEnums::Admin()->value, RolesEnums::Dcm()->value]))
                            ->action(function ($record) {

                                Functions::sendRejection($record);

                            }),

                        Action::make('notifier')
                            ->label("Notifier l'abonné")
                            ->requiresConfirmation()
                            ->modalHeading('Notifier l\'abonné?')
                            ->modalDescription("En faisant celà, vous informez l'abonné qu'il doit passer en agence compléter ou corriger certaines informations relatives à sa demande d'abonnement.")
                            ->icon('heroicon-o-envelope')
                            ->color(Color::Yellow)
                            ->visible(fn() => auth()->user()->hasRole([RolesEnums::Admin()->value, RolesEnums::Dcm()->value]))
                            ->action(function ($record) {

                                Functions::sendRecallSms($record);

                            })
                            ->visible(fn($record) => ($record->code_etat_contrat == 0 || $record->code_etat_contrat == 2) ? false : true),

                        Action::make('Télécharger')
                            ->label("contrat")
                            ->icon('heroicon-o-arrow-down-tray')
                            ->color('success')
                            ->visible(fn($record) => $record->code_etat_contrat == 0 ? true : false) //  0 => en cours || 1 => resilié || 2 => bloque
                            // ->url(fn( array $record) => route('contrat.pdf.ddl', $record))
                            ->action(function (Model $record) {

                                return response()->streamDownload(function () use ($record) {
                                    echo Pdf::loadHtml(
                                        Blade::render('contrat', ['record' => $record])
                                    )->stream();
                                }, $record->ref_contrat . $record->nom_abonne . '.pdf');
                            })


                    ]),

                // Action::make('valider')
                //     ->requiresConfirmation()
                //     ->color(Color::Green)
                //     ->icon('heroicon-o-check-circle')
                //     // ->modalHeading(fn($record) => __("Etes-vous sûr(e) de vouloir attribuer la bôite postale numéro ".$record->designation_bp. " à ".strtolower($record->prenom_abonne)." ". $record->nom_abonne." ".$record->raison_sociale." ?"))
                //     ->action(function ($record) {

                //         Functions::sendValidation($record);

                //     }),

                // Action::make('rejeter')
                //     ->requiresConfirmation()
                //     ->color(Color::Red)
                //     // ->modalHeading(fn($record) => __("Etes-vous sûr(e) de vouloir rejeter la demande de  ".strtolower($record->prenom_abonne)." ". $record->nom_abonne." ".$record->raison_sociale." pour la bôite postale numéro". $record->designation_bp." ?"))
                //     ->icon('heroicon-o-x-circle')
                //     ->action(function ($record) {

                //         Functions::sendRejection($record);

                //     }),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ])
            ->deferFilters();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContrats::route('/'),
            'create' => Pages\CreateContrat::route('/create'),
            // 'edit' => Pages\EditContrat::route('/{record}/edit'),
        ];
    }

    


}
