<?php

namespace App\Filament\Resources;

use App\Forms\Components\CfeField;
use Carbon\Carbon;
use App\Models\Etat;
use Filament\Tables;
use App\Models\Contrat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\BureauPoste;
use App\Functions\Functions;
use App\Models\BoitesPostale;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use App\Tables\Columns\IdentityColumn;
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

                        FileUpload::make("document_name")
                            ->label("")
                            ->preserveFilenames()
                            ->imageEditor(true)
                            ->columnSpanFull()
                            ->hiddenOn("view")

                    ])
                ,

                Fieldset::make("Informations de l'abonné")

                    ->hiddenOn("edit")
                    ->schema([

                        Grid::make(3)
                            ->schema([


                                TextInput::make('nom_abonne')
                                    ->label('Nom abonné')
                                    ->placeholder('-'),

                                TextInput::make('prenom_abonne')
                                    ->label('Prénom abonné')
                                    ->placeholder('-'),

                                TextInput::make('raison_sociale')
                                    ->label('Raison sociale')
                                    ->placeholder('-'),

                                TextInput::make('id_bp')
                                    ->label('id_bp')
                                    ->placeholder('-'),

                                TextInput::make('telephone')
                                    ->label('telephone')
                                    ->placeholder('-'),

                                TextInput::make('raison_sociale')
                                    ->label('Raison sociale')
                                    ->placeholder('-'),

                                TextInput::make('montant_reglement')
                                    ->label('montant_reglement')
                                    ->placeholder('-'),


                                TextInput::make('tel_fixe')
                                    ->label('tel_fixe')
                                    ->placeholder('-'),

                                TextInput::make('num_piece')
                                    ->label('num_piece')
                                    ->placeholder('-'),


                                TextInput::make('titre')
                                    ->label('titre')
                                    ->placeholder('-'),

                                DatePicker::make('date_deliv_piece')
                                    ->label('date_deliv_piece')
                                    ->format('d/m/Y')
                                    ->placeholder('-'),

                                TextInput::make('autorite_deliv_piece')
                                    ->label('autorite_deliv_piece')
                                    ->placeholder('-'),

                                TextInput::make('tel_mobile')
                                    ->label('tel_mobile')
                                    ->placeholder('-'),


                                TextInput::make('email')
                                    ->label('email')
                                    ->placeholder('-'),


                                TextInput::make('nom_maison')
                                    ->label('nom_maison')
                                    ->placeholder('-'),

                                TextInput::make('raison_sociale')
                                    ->label('Raison sociale')
                                    ->placeholder('-'),


                                TextInput::make('num_maison')
                                    ->label('num_maison')
                                    ->placeholder('-'),

                                TextInput::make('nom_rue')
                                    ->label('nom_rue')
                                    ->placeholder('-'),

                                TextInput::make('num_rue')
                                    ->label('num_rue')
                                    ->placeholder('-'),

                                TextInput::make('quartier')
                                    ->label('quartier')
                                    ->placeholder('-'),

                                TextInput::make('premier_resp')
                                    ->label('premier_resp')
                                    ->placeholder('-'),

                                DatePicker::make('datenais')
                                    ->label('datenais')
                                    ->format('d/m/Y')
                                    ->placeholder('-'),

                                TextInput::make('num_cpte')
                                    ->label('num_cpte')
                                    ->placeholder('-'),

                                TextInput::make('libelle_categ_prof')
                                    ->label('libelle_categ_prof')
                                    ->placeholder('-'),

                                TextInput::make('libelle_ville')
                                    ->label('Ville')
                                    ->placeholder('-'),

                                TextInput::make('banque')
                                    ->label('banque')
                                    ->placeholder('-'),

                                TextInput::make('email2')
                                    ->label('email2')
                                    ->placeholder('-'),

                                TextInput::make('libelle_piece')
                                    ->label('libelle_piece')
                                    ->placeholder('-'),

                                TextInput::make('infos_compl')
                                    ->label('infos_compl')
                                    ->placeholder('-'),

                                TextInput::make('id_operation')
                                    ->label('id_operation')
                                    ->placeholder('-'),

                            ]),

                    ]),

                Fieldset::make('Règlement')
                    ->hiddenOn("edit")
                    ->schema([

                        Grid::make(3)
                            ->schema([
                                TextInput::make('id_reglement')
                                    ->label('ID règlement')
                                    ->placeholder('-'),

                                DatePicker::make('date_reglement')
                                    ->label('Date du règlement')
                                    ->format('d/m/Y'),

                                TextInput::make('code_bureau')
                                    ->label('Bureau de poste'),

                                TextInput::make('designation_bp')
                                    ->label('Désignation boîte'),


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
                // Tables\Actions\EditAction::make(),

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
