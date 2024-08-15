<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Contrat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Functions\Functions;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Forms\Components\IdentityViewer;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ContratResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ContratResource\RelationManagers;

class ContratResource extends Resource
{
    protected static ?string $model = Contrat::class;

    protected static ?string $label = 'Validation des contrats';

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
                                TextInput::make('ref_contrat')
                                    ->label('Référence du contrat')
                                    ->placeholder('-'),

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

                                DatePicker::make('date_debut_contrat')
                                    ->label('Début du contrat')
                                    ->format('d/m/Y'),

                                DatePicker::make('date_fin_contrat')
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

                TextColumn::make('ref_contrat')
                ->label('Référence du contrat')
                ->placeholder('-'),

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

                TextColumn::make('date_debut_contrat')
                    ->label('Début du contrat')
                    ->badge()
                    ->color(Color::Green)
                    ->date('d/m/y'),

                TextColumn::make('date_fin_contrat')
                    ->label('Fin du contrat')
                    ->badge()
                    ->color(Color::Red)
                    ->date('d/m/y'),

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

                    TextColumn::make('id_operation')
                    ->label('operation')
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

                        Action::make('Télécharger')
                            ->label("contrat")
                            ->icon('heroicon-o-arrow-down-tray')
                            ->color('success')
                            // ->url(fn( array $record) => route('contrat.pdf.ddl', $record))
                            ->action(function (Model $record) {

                                return response()->streamDownload(function () use ($record) {
                                    echo Pdf::loadHtml(
                                        Blade::render('contrat', ['record' => $record])
                                    )->stream();
                                }, $record->ref_contrat.$record->nom_abonne.'.pdf');
                            })


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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContrats::route('/'),
            'create' => Pages\CreateContrat::route('/create'),
            'edit' => Pages\EditContrat::route('/{record}/edit'),
        ];
    }
}
