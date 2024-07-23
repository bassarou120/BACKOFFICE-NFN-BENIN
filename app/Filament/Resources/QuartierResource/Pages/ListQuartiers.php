<?php

namespace App\Filament\Resources\QuartierResource\Pages;

use App\Filament\Resources\QuartierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuartiers extends ListRecords
{
    protected static string $resource = QuartierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
