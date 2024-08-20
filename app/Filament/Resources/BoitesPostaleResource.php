<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Models\Etat;
use Filament\Forms\Components\RichEditor;
use Filament\Tables;
use App\Models\Ville;
use App\Models\Abonne;
use App\Models\Contrat;
use Filament\Forms\Form;
use App\Models\TypePiece;
use Filament\Tables\Table;
use App\Models\BureauPoste;
use App\Functions\Functions;
use App\Models\CategoriePro;
use App\Models\BoitesPostale;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use App\Forms\Components\CfeField;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use App\Tables\Columns\IdentityColumn;
use Illuminate\Support\Facades\Schema;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Forms\Components\IdentityViewer;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\BoitesPostaleResource\Pages;
use App\Filament\Resources\BoitesPostaleResource\RelationManagers\AbonneRelationManager;
use App\Filament\Resources\BoitesPostaleResource\RelationManagers\ContratRelationManager;


class BoitesPostaleResource extends Resource
{
    protected static ?string $label = 'Demandes en instance';
    protected static ?string $model = BoitesPostale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make("Piece d'identité")
                    ->columnSpanFull()
                    ->schema([

                        IdentityViewer::make("Identité")
                            ->label("Pièce d'identité"),

                        CfeField::make("cfe")
                        ->label("Carte CFE"),


                    ])
                ,

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
                                    ->options(CategoriePro::pluck("libelle_categ_prof","code_categ_prof")),

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
                    ->hiddenOn("edit")
                    ->schema([

                        Grid::make(3)
                            ->schema([

                                TextInput::make('id_operation')
                                    ->label('id_operation')
                                    ->placeholder('-'),

                                TextInput::make('id_reglement')
                                    ->label('ID règlement')
                                    ->placeholder('-')
                                    ->disabled()
                                    ->dehydrated(false),

                                    TextInput::make('montant_reglement')
                                    ->label('Montant du règlement')
                                    ->placeholder('-')
                                    ->disabled()
                                    ->dehydrated(false),

                                DatePicker::make('date_reglement')
                                    ->label('Date du règlement')
                                    ->displayFormat('d/m/Y')
                                    ->disabled()
                                    ->dehydrated(false),

                                TextInput::make('code_bureau')
                                    ->label('Bureau de poste')
                                    ->placeholder('-')
                                    ->disabled()
                                    ->dehydrated(false),

                                TextInput::make('designation_bp')
                                    ->label('Désignation boîte')
                                    ->disabled()
                                    ->dehydrated(false),
                                
                                TextInput::make('id_bp')
                                    ->label('ID de la boîte postale')
                                    ->placeholder('-')
                                    ->disabled()
                                    ->dehydrated(false),


                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                TextColumn::make("id_bp")
                    ->label("ID de la boîte")
                    ->placeholder("-")
                    ->searchable(query: function (Builder $query, string $search): Builder {

                        return $query->selectRaw('boite.boite_postale.id_bp')
                            ->whereRaw('LOWER(boite.boite_postale.id_bp) LIKE ?', ['%' . strtolower($search) . '%']);

                    }),

                TextColumn::make('id_reglement')
                    ->label('ID règlement')
                    ->placeholder('-'),

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

                TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->badge()
                    ->placeholder('-'),

                TextColumn::make('date_reglement')
                    ->label('Date de règlement')
                    ->badge()
                    ->color(Color::Blue)
                    ->date('d/m/y')
                    ->placeholder('-'),

                TextColumn::make('montant_reglement')
                    ->label('Montant'),

                    


                TextColumn::make('code_bureau')
                    ->label('Bureau de poste')
                    ->formatStateUsing(function ($state) {


                        $bureauPoste = DB::table("boite.bureau")->whereRaw("code_bureau = ?", [$state])->first();

                        $libellePoste = $bureauPoste ? $bureauPoste->designation_buro : $state;

                        return $libellePoste;
                    })
                    ->placeholder('-'),

                TextColumn::make('designation_bp')
                    ->label('Désignation boîte')
                    ->badge()
                    ->color(Color::Blue)
                    ->placeholder('-'),

                // IdentityColumn::make("piece")

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

                        ////////////////////////////////////////////
                        "code_categ_prof" => $data["code_categ_prof"],

                        /////////////////////////

                        "code_type_piece" => $data["code_type_piece"],
                        "code_ville" => $data["code_ville"],
                        "banque" => $data["banque"],
                        "email2" => $data["email2"],


                    ]);

                    return $record;
                }),

                Action::make("enregistrer")
                    ->action(function ($record) {

                        $sequence = DB::getSequence();

                        $contratSequence = $sequence->nextvalue('BOITE.CONTRAT_SEQ');

 
                        try {

                            Contrat::firstOrCreate([

                                "ref_contrat" => $record->code_bureau . str_pad($record->designation_bp, 5, '0', STR_PAD_RIGHT) . str_pad($record->id_abonne, 6, '0', STR_PAD_LEFT) . (Carbon::parse($record->date_reglement))->format('Y') . str_pad($contratSequence, 6, '0', STR_PAD_LEFT),
                                "code_etat_contrat" => 3,
                                "contrat_source" => null,
                                "date_debut_contrat" => $record->date_reglement,
                                "date_derniere_facture" => $record->date_reglement,
                                "date_fin_contrat" => (Carbon::parse($record->date_reglement))->addYears(2),//  TODO: $record->validite_annee
                                "date_resiliation" => null,
                                "date_resiliation_off" => null,
                                "id_abonne" => $record->id_abonne,
                                "id_bp" => $record->id_bp,
                                "id_operation" => $record->id_operation,
                                "id_service" => 1,
                                "periodicite_facturation" => $record->periodicite_facturation,
                                "utilisateur" => strtoupper(auth()->user()->name),

                            ]);
                        } catch (\Exception $e) {

                        }


                        Notification::make("created")
                            ->title("Enregistré(e)")
                            ->body("La demande a été enregisttrée")
                            ->color(Color::Green)
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()
                    ->extraModalFooterActions([
                            

                                Action::make("enregistrer")
                                ->action(function ($record) {
            
                                    $sequence = DB::getSequence();
            
                                    $contratSequence = $sequence->nextvalue('BOITE.CONTRAT_SEQ');
            
                                    try {
            
                                        Contrat::firstOrCreate([
            
                                            "ref_contrat" => $record->code_bureau . str_pad($record->designation_bp, 5, '0', STR_PAD_RIGHT) . str_pad($record->id_abonne, 6, '0', STR_PAD_LEFT) . (Carbon::parse($record->date_reglement))->format('Y') . str_pad($contratSequence, 6, '0', STR_PAD_LEFT),
                                            "code_etat_contrat" => 3,
                                            "contrat_source" => null,
                                            "date_debut_contrat" => $record->date_reglement,
                                            "date_derniere_facture" => $record->date_reglement,
                                            "date_fin_contrat" => (Carbon::parse($record->date_reglement))->addYears($record->validite_annee),
                                            "date_resiliation" => null,
                                            "date_resiliation_off" => null,
                                            "id_abonne" => $record->id_abonne,
                                            "id_bp" => $record->id_bp,
                                            "id_operation" => $record->id_operation,
                                            "id_service" => 1,
                                            "periodicite_facturation" => $record->periodicite_facturation,
                                            "utilisateur" => strtoupper(auth()->user()->name),
            
                                        ]);
                                    } catch (\Exception $e) {
            
                                    }
            
            
                                    Notification::make("created")
                                        ->title("Enregistré(e)")
                                        ->body("La demande a été enregisttrée")
                                        ->color(Color::Green)
                                        ->send();

                                    return redirect(route("filament.admin.resources.boites-postales.index"));
                                }),
                        ]),

           
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
            ContratRelationManager::class,
            AbonneRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBoitesPostales::route('/'),
            'create' => Pages\CreateBoitesPostale::route('/create'),
            // 'edit' => Pages\EditBoitesPostale::route('/{record}/edit'),
        ];
    }
}
