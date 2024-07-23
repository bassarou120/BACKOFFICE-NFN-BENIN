<?php

namespace App\Filament\Resources\StatAdherantByArrondResource\Pages;

use App\Filament\Resources\StatAdherantByArrondResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatAdherantByArrond extends EditRecord
{
    protected static string $resource = StatAdherantByArrondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
