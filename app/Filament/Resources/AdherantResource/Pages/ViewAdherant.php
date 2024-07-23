<?php

namespace App\Filament\Resources\AdherantResource\Pages;

use App\Filament\Resources\AdherantResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdherant extends ViewRecord
{
    protected static string $resource = AdherantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
