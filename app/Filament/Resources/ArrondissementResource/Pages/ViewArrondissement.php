<?php

namespace App\Filament\Resources\ArrondissementResource\Pages;

use App\Filament\Resources\ArrondissementResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewArrondissement extends ViewRecord
{
    protected static string $resource = ArrondissementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
