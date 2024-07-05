<?php

namespace App\Filament\Resources;

use App\Forms\Components\IdentityViewer;
use App\Tables\Columns\IdentityColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use PDO;
use Carbon\Carbon;
use Filament\Forms;
use App\Models\Etat;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\BureauPoste;
use Illuminate\Support\Str;
use App\Models\BoitesPostale;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use App\Procedures\StoredProcedures;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BoitesPostaleResource\Pages;
use App\Filament\Resources\BoitesPostaleResource\RelationManagers;
use App\Filament\Resources\BoitesPostaleResource\RelationManagers\AbonneRelationManager;
use App\Filament\Resources\BoitesPostaleResource\RelationManagers\ContratRelationManager;

class BoitesPostaleResource extends Resource
{
    protected static ?string $model = BoitesPostale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    const ACTIVATED = 2;

    const REJECTED = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([



                Fieldset::make("Piece d'identité")
                    ->schema([

                        IdentityViewer::make("")
                            ->columnSpanFull()
                            ->viewData(['src' => 'https://ui-avatars.com/api/?name=John+Doe']),
                    ]),

                Fieldset::make("Informations de l'abonné")
                    ->schema([

                        Grid::make(3)
                            ->schema([
                                TextInput::make("nom_abonne")
                                    ->label("Nom abonné")
                                    ->placeholder("-"),

                                TextInput::make("prenom_abonne")
                                    ->label("Prénom abonné"),

                                TextInput::make("raison_sociale")
                                    ->label("Raison sociale")
                                    ->placeholder("-"),
                            ])



                        // Select::make("code_etat_bp")
                        //     ->label("Etat")
                        //     ->native(false)
                        //     ->options(Etat::pluck("libelle_etat_bp", "code_etat_bp")),
                    ]),

                Fieldset::make("Règlement")
                    ->schema([

                        Grid::make(3)
                            ->schema([
                                TextInput::make("id_reglement")
                                    ->label("ID paiement")
                                    ->placeholder("-"),

                                DatePicker::make("date_reglement")
                                    ->label("Date du règlement")
                                    ->format('d/m/Y'),


                                TextInput::make("code_bureau")
                                    ->label("Bureau de poste"),

                                TextInput::make("designation_bp")
                                    ->label("Numéro boîte"),

                                DatePicker::make("debut_contrat")
                                    ->label("Début du contrat")
                                    ->format('d/m/Y'),

                                DatePicker::make("fin_contrat")
                                    ->label("Fin du contrat")
                                    ->format('d/m/Y'),
                            ])
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

                TextColumn::make("id_reglement")
                    ->label("ID paiement")
                    ->placeholder("-"),


                TextColumn::make("nom_abonne")
                    ->label("Nom abonné")
                    ->placeholder("-"),

                TextColumn::make("prenom_abonne")
                    ->label("Prénom abonné")
                    ->placeholder("-"),

                TextColumn::make("raison_sociale")
                    ->label("Raison sociale")
                    ->placeholder("-"),

                TextColumn::make("telephone")
                    ->label("Téléphone")
                    ->badge()
                    ->placeholder("-"),


                TextColumn::make("date_reglement")
                    ->label("Date de règlement")
                    ->date("d/m/y")
                    ->placeholder("-"),

                TextColumn::make("montant_reglement")
                    ->label("Montant"),

                TextColumn::make("debut_contrat")
                    ->label("Début du contrat")
                    ->badge()
                    ->color(Color::Green)
                    ->date("d/m/y"),

                TextColumn::make("fin_contrat")
                    ->label("Fin du contrat")
                    ->badge()
                    ->color(Color::Red)
                    ->date("d/m/y"),

                TextColumn::make("code_bureau")
                    ->label("Bureau de poste")
                    ->formatStateUsing(fn($state) => BureauPoste::find($state) ? BureauPoste::find($state)->libelle_poste : $state)
                    ->placeholder("-"),

                TextColumn::make("designation_bp")
                    ->label("Numéro boîte")
                    ->badge()
                    ->color(Color::Blue)
                    ->placeholder("-"),

                // IdentityColumn::make("piece")


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Action::make("api")
                    ->action(function ($record) {
                        dd($record);
                    }),

                Action::make("valider")
                    ->color(Color::Green)
                    ->action(function ($record) {

                        // $record->update([
                        //     "code_etat_bp" => self::ACTIVATED //atribuée
                        // ]);
            
                        $refSms = Str::random(10);

                        $telephone = 22891568182;

                        $message = "Mr " . strtoupper($record->nom_abonne) . " " . strtoupper($record->prenom_abonne) . ", NOUS AVONS LE PLAISIR DE VOUS ANNONCER QUE LA BOITE POSTALE NUMERO " . $record->designation_bp . " VOUS A ETE ATTRIBUEE.";

                        $dateSms = Carbon::parse(today())->format("d/m/y");

                        $origine = "0";

                        $bureau = BureauPoste::find($record->code_bureau);

                        if (!is_null($bureau)) {
                            $origine = $bureau->libelle_poste;
                        } else
                            $origine = $record->code_bureau;

                        Notification::make("valide")
                            ->body("Boîte postale attribuée")
                            ->color(Color::Green)
                            ->send();

                        StoredProcedures::sendSms($refSms, $telephone, $message, $dateSms, $origine);

                    }),

                Action::make("rejeter")
                    ->color(Color::Red)
                    ->action(function ($record) {
                        // dd($record);
                        // $record->update([
                        //     "code_etat_bp" => self::REJECTED //atribuée
                        // ]);
            
                        $refSms = Str::random(10);

                        $telephone = 22891568182;  //$record->telephone;
            
                        $message = "Mr " . strtoupper($record->nom_abonne) . " " . strtoupper($record->prenom_abonne) . ", NOUS AVONS LE REGRET DE VOUS ANNONCER QUE VOTRE DEMANDE DE BOITE POSTALE A ETE REJETEE.";

                        $dateSms = Carbon::parse(today())->format("d/m/y");

                        $bureau = BureauPoste::find($record->code_bureau);

                        if (!is_null($bureau)) {
                            $origine = $bureau->libelle_poste;
                        } else
                            $origine = $record->code_bureau;

                        Notification::make("REJETE")
                            ->body("Boîte postale refusée")
                            ->color(Color::Red)
                            ->send();

                        StoredProcedures::sendSms($refSms, $telephone, $message, $dateSms, $origine);

                    }),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),

                    // BulkAction::make('Valider')
                    //     ->color(Color::Green)
                    //     ->requiresConfirmation()
                    //     ->action(function (Collection $records) {

                    //         $records->each->update([
                    //             "code_etat_bp" => self::ACTIVATED //atribuée
                    //         ]);

                    //         Notification::make("valide")
                    //             ->body("Boîte postale attribuée")
                    //             ->color(Color::Green)
                    //             ->send();

                    //     }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ContratRelationManager::class,
            AbonneRelationManager::class
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
