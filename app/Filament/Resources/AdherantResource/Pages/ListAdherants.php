<?php

namespace App\Filament\Resources\AdherantResource\Pages;

use App\Filament\Resources\AdherantResource;
use App\Models\Adherant;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;


class ListAdherants extends ListRecords
{
    protected static string $resource = AdherantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            ExportAction::make()
                ->label("Exporter en Excel")
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(fn ($resource) => $resource::getModelLabel() . '-' . date('Y-m-d'))
                        ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                        ->withColumns([
                            Column::make('updated_at'),
                        ]),


                ]),


        ];

    }


    protected function getTableBulkActions(): array
    {
        return [
            BulkAction::make('export')
                ->action( function (Collection $records) {

                })
                ->deselectRecordsAfterCompletion()
        ];
    }


    public function getTabs(): array
    {
        return [
            'Tous'=>Tab::make()
                ->badge(Adherant::query()
                    ->where('status', '=', "APPROUVER")
                    ->count()),
            'Nouveau membre de la semaine' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>=', now()->subWeek()) ->where('status', '=', "APPROUVER"))
                ->badge(Adherant::query()->where('created_at', '>=', now()->subWeek()) ->where('status', '=', "APPROUVER")->count()),
            'Nouveau membre du mois' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>=', now()->subMonth()) ->where('status', '=', "APPROUVER"))
                ->badge(Adherant::query()->where('created_at', '>=', now()->subMonth()) ->where('status', '=', "APPROUVER")->count()),
            "Nouveau membre de l'année" => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>=', now()->subYear()) ->where('status', '=', "APPROUVER"))
                ->badge(Adherant::query()->where('created_at', '>=', now()->subYear()) ->where('status', '=', "APPROUVER")->count()),
        ];
    }


}
