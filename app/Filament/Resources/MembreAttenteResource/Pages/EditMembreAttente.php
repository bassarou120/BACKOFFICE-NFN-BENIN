<?php

namespace App\Filament\Resources\MembreAttenteResource\Pages;

use App\Filament\Resources\MembreAttenteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMembreAttente extends EditRecord
{
    protected static string $resource = MembreAttenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
