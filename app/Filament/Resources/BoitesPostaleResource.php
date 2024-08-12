<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Models\Etat;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\BureauPoste;
use App\Functions\Functions;
use App\Models\BoitesPostale;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use App\Tables\Columns\IdentityColumn;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use App\Forms\Components\IdentityViewer;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\BoitesPostaleResource\Pages;
use App\Filament\Resources\BoitesPostaleResource\RelationManagers\AbonneRelationManager;
use App\Filament\Resources\BoitesPostaleResource\RelationManagers\ContratRelationManager;

class BoitesPostaleResource extends Resource
{
    protected static ?string $model = BoitesPostale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make("Piece d'identité")
                ->columnSpanFull()
                    ->schema([

                        IdentityViewer::make(""),
                        
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
                                    ->label('Prénom abonné'),

                                TextInput::make('raison_sociale')
                                    ->label('Raison sociale')
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
                                    ->label('Numéro boîte'),

                                DatePicker::make('debut_contrat')
                                    ->label('Début du contrat')
                                    ->format('d/m/Y'),

                                DatePicker::make('fin_contrat')
                                    ->label('Fin du contrat')
                                    ->format('d/m/Y'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // TextColumn::make("id_bp")
                //     ->label("ID de la boîte")
                //     ->placeholder("-")
                //     ->searchable(query: function (Builder $query, string $search): Builder {

                //         return $query->selectRaw('boite.boite_postale.id_bp')
                //             ->whereRaw('LOWER(boite.boite_postale.id_bp) LIKE ?', ['%' . strtolower($search) . '%']);

                //     }),

                TextColumn::make('id_reglement')
                    ->label('ID règlement')
                    ->placeholder('-'),

                TextColumn::make('nom_abonne')
                    ->label('Nom abonné')
                    ->placeholder('-')
                    ->searchable(query: function (Builder $query, string $search): Builder {

                        return $query->selectRaw('nom')->whereRaw('LOWER(nom) LIKE ?', ['%'.strtolower($search).'%']);

                    }),

                TextColumn::make('prenom_abonne')
                    ->label('Prénom abonné')
                    ->placeholder('-')
                    ->searchable(query: function (Builder $query, string $search): Builder {

                        return $query->selectRaw('prenoms')->whereRaw('LOWER(prenoms) LIKE ?', ['%'.strtolower($search).'%']);

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

                TextColumn::make('debut_contrat')
                    ->label('Début du contrat')
                    ->badge()
                    ->color(Color::Green)
                    ->date('d/m/y'),

                TextColumn::make('fin_contrat')
                    ->label('Fin du contrat')
                    ->badge()
                    ->color(Color::Red)
                    ->date('d/m/y'),

                TextColumn::make('code_bureau')
                    ->label('Bureau de poste')
                    ->formatStateUsing(function ($state) {


                        $bureauPoste = DB::table("spt.bureau_poste")->whereRaw("code_bureau = ?", [$state])->first();

                        $libellePoste = $bureauPoste ? $bureauPoste->libelle_poste : $state;

                        return $libellePoste;
                    })
                    ->placeholder('-'),

                TextColumn::make('designation_bp')
                    ->label('Numéro boîte')
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
                Tables\Actions\EditAction::make(),

                Tables\Actions\ViewAction::make()
                    ->extraModalFooterActions([
                        Action::make('valider')
                            ->icon('heroicon-o-check-circle')
                            ->requiresConfirmation()
                            ->color(Color::Green)
                            ->action(function ($record) {

                                Functions::sendValidation($record);

                            }),

                        Action::make('rejeter')
                            ->requiresConfirmation()
                            ->icon('heroicon-o-x-circle')
                            ->color(Color::Red)
                            ->action(function ($record) {

                                Functions::sendRejection($record);

                            }),
                    ]),

                Action::make('valider')
                    ->requiresConfirmation()
                    ->color(Color::Green)
                    ->icon('heroicon-o-check-circle')
                    // ->modalHeading(fn($record) => __("Etes-vous sûr(e) de vouloir attribuer la bôite postale numéro ".$record->designation_bp. " à ".strtolower($record->prenom_abonne)." ". $record->nom_abonne." ".$record->raison_sociale." ?"))
                    ->action(function ($record) {
                         
                        Functions::sendValidation($record);

                    }),

                Action::make('rejeter')
                ->requiresConfirmation()
                    ->color(Color::Red)
                    // ->modalHeading(fn($record) => __("Etes-vous sûr(e) de vouloir rejeter la demande de  ".strtolower($record->prenom_abonne)." ". $record->nom_abonne." ".$record->raison_sociale." pour la bôite postale numéro". $record->designation_bp." ?"))
                    ->icon('heroicon-o-x-circle')
                    ->action(function ($record) {
 
                        Functions::sendRejection($record);

                    }),
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
