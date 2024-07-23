<?php

namespace App\Filament\Resources\StatAdherantByCommuneResource\Pages;

use App\Filament\Resources\StatAdherantByCommuneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatAdherantByCommune extends EditRecord
{
    protected static string $resource = StatAdherantByCommuneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
