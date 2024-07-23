<?php

namespace App\Filament\Resources\AdherantResource\Pages;

use App\Filament\Resources\AdherantResource;
use App\Models\Role;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateAdherant extends CreateRecord
{
    protected static string $resource = AdherantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {



        $data['photo_identite']='/'. $data['photo_identite'];
        $data['piece_photo_identite']='/'. $data['piece_photo_identite'];


//      dd($data);
        return $data;
    }
}
