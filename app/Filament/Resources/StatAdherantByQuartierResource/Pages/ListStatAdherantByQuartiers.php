<?php

namespace App\Filament\Resources\StatAdherantByQuartierResource\Pages;

use App\Filament\Resources\StatAdherantByQuartierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListStatAdherantByQuartiers extends ListRecords
{
    protected static string $resource = StatAdherantByQuartierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

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

    public function getHeading(): string
    {
        $company = auth()->user()->name;
        return "Statistique par Quartier";
    }
}
