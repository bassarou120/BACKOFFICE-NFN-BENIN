<?php

namespace App\Filament\Resources\RoleCommuneResource\Pages;

use App\Filament\Resources\RoleCommuneResource;
use App\Models\RoleCommune;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRoleCommune extends CreateRecord
{
    protected static string $resource = RoleCommuneResource::class;


//    protected function mutateFormDataBeforeCreate(array $data): array
//    {
//
//        dd($data);
//
//       return $data;
//    }


    protected function handleRecordCreation(array $data): Model
    {

        dd($data);
        return parent::handleRecordCreation($data); // TODO: Change the autogenerated stub
    }



//    protected function handleRecordCreation(array $data)
//    {
//        $yourModel = YourModel::create($data);
//
//        // Sync the relationship for multiple communes
//        $yourModel->communes()->sync($data['commune_id']);
//
//        return $yourModel;
//    }


}
