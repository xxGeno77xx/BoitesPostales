<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BoitesPostaleResource\Pages;
use App\Filament\Resources\BoitesPostaleResource\RelationManagers\AbonneRelationManager;
use App\Filament\Resources\BoitesPostaleResource\RelationManagers\ContratRelationManager;
use App\Forms\Components\IdentityViewer;
use App\Functions\Functions;
use App\Models\BoitesPostale;
use App\Models\BureauPoste;
use App\Models\Etat;
use App\Tables\Columns\IdentityColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BoitesPostaleResource extends Resource
{
    protected static ?string $model = BoitesPostale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make("Piece d'identité")
                    ->schema([

                        IdentityViewer::make('')
                            ->columnSpanFull()
                        // ->viewData(['src' => 'https://ui-avatars.com/api/?name=John+Doe'])
                        ,
                    ]),

                Fieldset::make("Informations de l'abonné")
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

                        // Select::make("code_etat_bp")
                        //     ->label("Etat")
                        //     ->native(false)
                        //     ->options(Etat::pluck("libelle_etat_bp", "code_etat_bp")),
                    ]),

                Fieldset::make('Règlement')
                    ->schema([

                        Grid::make(3)
                            ->schema([
                                TextInput::make('id_reglement')
                                    ->label('ID paiement')
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
                //     ->placeholder("-"),

                TextColumn::make('id_reglement')
                    ->label('ID paiement')
                    ->placeholder('-'),

                TextColumn::make('nom_abonne')
                    ->label('Nom abonné')
                    ->placeholder('-'),

                TextColumn::make('prenom_abonne')
                    ->label('Prénom abonné')
                    ->placeholder('-'),

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
                    ->formatStateUsing(fn ($state) => BureauPoste::find($state) ? BureauPoste::find($state)->libelle_poste : $state)
                    ->placeholder('-'),

                TextColumn::make('designation_bp')
                    ->label('Numéro boîte')
                    ->badge()
                    ->color(Color::Blue)
                    ->placeholder('-'),

                // IdentityColumn::make("piece")

            ])
            ->filters([
                //
            ])
            ->actions([
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
                    ->color(Color::Green)
                    ->icon('heroicon-o-check-circle')
                    ->action(function ($record) {

                        Functions::sendValidation($record);

                    }),

                Action::make('rejeter')
                    ->color(Color::Red)
                    ->icon('heroicon-o-x-circle')
                    ->action(function ($record) {

                        Functions::sendRejection($record);

                    }),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
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
