<?php

namespace App\Filament\Resources\QuartierResource\Pages;

use App\Filament\Resources\QuartierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuartier extends EditRecord
{
    protected static string $resource = QuartierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
