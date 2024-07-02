<?php

namespace App\Filament\Resources\BoitesPostaleResource\Pages;

use App\Filament\Resources\BoitesPostaleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBoitesPostales extends ListRecords
{
    protected static string $resource = BoitesPostaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
