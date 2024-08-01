<?php

namespace App\Filament\Resources\MembreAttenteResource\Pages;

use App\Filament\Resources\MembreAttenteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListMembreAttentes extends ListRecords
{
    protected static string $resource = MembreAttenteResource::class;

    public function getHeading(): string
    {

        return "Liste des membres en Attente de validation";
    }

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),

            ExportAction::make()
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(fn ($resource) => $resource::getModelLabel() . '-' . date('Y-m-d'))
                        ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                        ->withColumns([
                            Column::make('updated_at'),
                        ])
                ]),
        ];
    }


//    protected function getTableRowClasses($record): ?string
//    {
//        if ($record->status == 'APPROUVER') {
//            return 'bg-green-100';
//        }
//
//        if ($record->status == 'EN ATTENTE') {
//            return 'bg-red-100';
//        }
//
//        return null;
//    }

}
