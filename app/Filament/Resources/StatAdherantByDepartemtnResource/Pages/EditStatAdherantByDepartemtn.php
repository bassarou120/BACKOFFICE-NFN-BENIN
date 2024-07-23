<?php

namespace App\Filament\Resources\StatAdherantByDepartemtnResource\Pages;

use App\Filament\Resources\StatAdherantByDepartemtnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatAdherantByDepartemtn extends EditRecord
{
    protected static string $resource = StatAdherantByDepartemtnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
