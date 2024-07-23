<?php

namespace App\Filament\Resources\CarteMembreResource\Pages;

use App\Filament\Resources\CarteMembreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarteMembre extends EditRecord
{
    protected static string $resource = CarteMembreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
