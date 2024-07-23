<?php

namespace App\Filament\Resources\CarteMembreResource\Pages;

use App\Filament\Resources\CarteMembreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarteMembres extends ListRecords
{
    protected static string $resource = CarteMembreResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
