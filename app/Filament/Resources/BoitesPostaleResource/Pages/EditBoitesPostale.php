<?php

namespace App\Filament\Resources\BoitesPostaleResource\Pages;

use App\Filament\Resources\BoitesPostaleResource;
use Filament\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditBoitesPostale extends EditRecord
{
    protected static string $resource = BoitesPostaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),

            // Action::make("dd")
            // ->action(fn($record)=> dd($record))
        ];
    }

    protected function authorizeAccess(): void
    {
        abort(403);
        // abort_if(! $userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
