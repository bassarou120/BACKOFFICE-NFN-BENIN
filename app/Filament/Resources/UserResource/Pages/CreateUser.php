<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Role;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {

//        dd($data);

        $data['password'] = Hash::make($data['password']);
//        $data['role_id'] = Role::ROLE_ADMINISTRATOR;
//        dd($data);
        return $data;
    }
}
