<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatAdherantByArrondResource\Pages;
use App\Filament\Resources\StatAdherantByArrondResource\RelationManagers;
use App\Models\StatAdherantByArrond;
use App\Models\Statarrondissement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatAdherantByArrondResource extends Resource
{
    protected static ?string $model = Statarrondissement::class;

    protected static ?string $navigationGroup = 'Statistiques';
    protected static ?string $navigationLabel="Statistique par Arrondissement";
    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';
    protected static ?int $navigationSort=6;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatAdherantByArronds::route('/'),
            'create' => Pages\CreateStatAdherantByArrond::route('/create'),
            'edit' => Pages\EditStatAdherantByArrond::route('/{record}/edit'),
        ];
    }
}
