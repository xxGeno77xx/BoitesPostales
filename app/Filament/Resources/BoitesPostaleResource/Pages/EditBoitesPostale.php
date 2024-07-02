<?php

namespace App\Filament\Resources\BoitesPostaleResource\Pages;

use App\Filament\Resources\BoitesPostaleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBoitesPostale extends EditRecord
{
    protected static string $resource = BoitesPostaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
