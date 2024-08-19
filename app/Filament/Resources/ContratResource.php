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
use App\Models\TypePiece;
use Filament\Tables\Table;
use App\Functions\Functions;
use App\Models\CategoriePro;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use App\Procedures\StoredProcedures;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
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
                Fieldset::make("Piece d'identité")
                    ->columnSpanFull()
                    ->schema([

                        IdentityViewer::make(""),

                        FileUpload::make("document_name")
                            ->label("")
                            ->preserveFilenames()
                            ->imageEditor(true)
                            ->columnSpanFull()
                            ->hidden()

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

                                RichEditor::make('infos_compl')
                                    ->label('Informations complémentaires')
                                    ->placeholder('-')
                                    ->columnSpanFull()
                                    ->disableToolbarButtons([
                                      
                                    ]),

                                

                                    

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

                // TextColumn::make("id_bp")
                //     ->label("ID de la boîte")
                //     ->placeholder("-")
                //     ->searchable(query: function (Builder $query, string $search): Builder {

                //         return $query->selectRaw('boite.boite_postale.id_bp')
                //             ->whereRaw('LOWER(boite.boite_postale.id_bp) LIKE ?', ['%' . strtolower($search) . '%']);

                //     }),

                // TextColumn::make('id_reglement')
                //     ->label('ID règlement')
                //     ->placeholder('-'),

                BadgeColumn::make("libelle_etat_contrat")
                    ->label("Statut du contrat")
                    ->color(function($state){
                        $return = null;
                        switch($state){
                            case "CONTRAT EN COURS" : $return = Color::Green;
                            break;

                            case "CONTRAT RESILIE" : $return = Color::Orange;
                            break;

                            case "CONTRAT BLOQUE" : $return = Color::Red;
                            break;

                            case "CONTRAT INITIE" : $return = Color::Blue;
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

                // TextColumn::make('montant_reglement')
                //     ->label('Montant'),

                TextColumn::make('date_debut_contrat')
                    ->label('Début du contrat')
                    ->badge()
                    ->color(Color::Green)
                    ->date('d/m/Y'),

                TextColumn::make('date_fin_contrat')
                    ->label('Fin du contrat')
                    ->badge()
                    ->color(Color::Red)
                    ->date('d/m/Y'),

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

                    // TextColumn::make('id_operation')
                    // ->label('operation')
                    // ->badge()
                    // ->color(Color::Blue)
                    // ->placeholder('-'),


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

                            Action::make('notifier')
                            ->form([
                                Placeholder::make("categ_pro")
                                    ->label("Catégorie professionnelle")
                                    ->content(fn($record) => $record->libelle_categ_prof),

                                TextInput::make("tarif")
                                    ->formatStateUsing(function($record){

                                        $response = Http::post('192.168.60.43:8080/boitepostale-api/boitemanagement/tarifAbonnement', [
                                            'codeCategProf' => 'Steve',
                                            'codeBureau' => 'Network Administrator',
                                            'duree' => 'Network Administrator',
                                            'codeTypeOp' => 'Network Administrator',
                                            
                                        ]);
                                    })
                                    ->disabled()

                            ])
                            ->requiresConfirmation()
                            ->icon('heroicon-o-envelope')
                            ->color(Color::Yellow)
                            ->action(function ($record) {

                            //    $result = StoredProcedures::getTarifs(4,1,1,1, '01/01/24',  '01/01/26', 924,2,1,1);

                            }),

                        Action::make('Télécharger')
                            ->label("contrat")
                            ->icon('heroicon-o-arrow-down-tray')
                            ->color('success')
                            ->visible(fn($record) => $record->code_etat_contrat == 0? true: false) //  0 => en cours || 1 => resilié || 2 => bloque
                            // ->url(fn( array $record) => route('contrat.pdf.ddl', $record))
                            ->action(function (Model $record) {

                                return response()->streamDownload(function () use ($record) {
                                    echo Pdf::loadHtml(
                                        Blade::render('contrat', ['record' => $record])
                                    )->stream();
                                }, $record->ref_contrat.$record->nom_abonne.'.pdf');
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
