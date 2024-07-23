<?php

namespace App\Filament\Resources\StatAdherantByQuartierResource\Pages;

use App\Filament\Resources\StatAdherantByQuartierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatAdherantByQuartier extends EditRecord
{
    protected static string $resource = StatAdherantByQuartierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
