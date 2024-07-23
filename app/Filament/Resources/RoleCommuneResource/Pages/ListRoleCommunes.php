<?php

namespace App\Filament\Resources\RoleCommuneResource\Pages;

use App\Filament\Resources\RoleCommuneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoleCommunes extends ListRecords
{
    protected static string $resource = RoleCommuneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
