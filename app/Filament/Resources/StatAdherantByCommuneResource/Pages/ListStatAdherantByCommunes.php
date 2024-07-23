<?php

namespace App\Filament\Resources\StatAdherantByCommuneResource\Pages;

use App\Filament\Resources\StatAdherantByCommuneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListStatAdherantByCommunes extends ListRecords
{
    protected static string $resource = StatAdherantByCommuneResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),

            ExportAction::make()
                ->label("Exporter en Excel")
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

    public function getHeading(): string
    {
        $company = auth()->user()->name;
        return "Statistique par communes";
    }
}
