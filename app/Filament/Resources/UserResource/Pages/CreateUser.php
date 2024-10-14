<?php

namespace  Phpsa\FilamentAuthentication\Resources\UserResource\Pages;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Config;
use Filament\Resources\Pages\CreateRecord;
use Phpsa\FilamentAuthentication\Events\UserCreated;
use Phpsa\FilamentAuthentication\FilamentAuthentication;

class CreateUser extends CreateRecord
{
    public static function getResource(): string
    {
        return FilamentAuthentication::getPlugin()->getResource('UserResource');
    }

    protected function afterCreate(): void
    {
        Event::dispatch(new UserCreated($this->record));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data["username"] = strtoupper($data["username"]);
        $data["name"] = strtoupper($data["username"]);
        $data["email"] = strtoupper($data["username"]).Str::random(10).'@'.Str::random(3).'.com';
        return $data;
    }
}
