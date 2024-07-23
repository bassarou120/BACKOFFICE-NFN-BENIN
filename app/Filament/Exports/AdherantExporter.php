<?php

namespace App\Filament\Exports;

use App\Models\Adherant;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AdherantExporter extends Exporter
{
    protected static ?string $model = Adherant::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('departement.id'),
            ExportColumn::make('commune.id'),
            ExportColumn::make('arrondissement.id'),
            ExportColumn::make('quartier.id'),
            ExportColumn::make('nom'),
            ExportColumn::make('prenom'),
            ExportColumn::make('date_naissance'),
            ExportColumn::make('lieu_residence'),
            ExportColumn::make('adresse'),
            ExportColumn::make('telephone'),
            ExportColumn::make('email'),
            ExportColumn::make('photo_identite'),
            ExportColumn::make('piece_photo_identite'),
            ExportColumn::make('niveau_instruction'),
            ExportColumn::make('activite_profession'),
            ExportColumn::make('ambition_politique'),
            ExportColumn::make('status'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your adherant export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
