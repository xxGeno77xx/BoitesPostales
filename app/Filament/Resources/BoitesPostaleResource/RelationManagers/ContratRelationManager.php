<?php

namespace App\Filament\Resources\BoitesPostaleResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Contrat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ContratRelationManager extends RelationManager
{
    protected static string $relationship = 'contrat';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ref_contrat')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        // ->recordTitleAttribute("boite.contrat.ref_contrat")
             ->columns([
                Tables\Columns\TextColumn::make('ref_contrat')->searchable(),
                Tables\Columns\TextColumn::make('utilisateur'),
                Tables\Columns\TextColumn::make('id_service'),
                Tables\Columns\TextColumn::make('id_abonne'),
                Tables\Columns\TextColumn::make('id_operation'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make() ,
              
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    
    // protected function getTableQuery(): Builder  
    // {
    //  $parent = $this->getOwnerRecord();

    //     return Contrat::join("boite.boite_postale", "boite.boite_postale.id_bp", "=" ,"boite.contrat.id_bp")
    //         // ->where("boite.boite_postale.id_bp", $parent->id_bp)
    //         ->select("boite.contrat.ref_contrat" , "boite.contrat.id_operation");
    // }
}
