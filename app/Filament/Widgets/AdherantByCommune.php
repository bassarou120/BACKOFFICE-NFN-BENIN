<?php

namespace App\Filament\Resources\AdherantResource\Widgets;

use App\Models\Adherant;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AdherantByCommune extends BaseWidget
{
    protected static ?string $heading =" Nombre d'adherants par Communes";
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query( Adherant::query()
            )
            ->columns([
                Tables\Columns\TextColumn::make('nom'),
            ]);
    }
}
