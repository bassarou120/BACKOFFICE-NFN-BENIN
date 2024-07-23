<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuartierResource\Pages;
use App\Filament\Resources\QuartierResource\RelationManagers;
use App\Models\Quartier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuartierResource extends Resource
{
    protected static ?string $model = Quartier::class;

    protected static ?string $navigationGroup = 'Paramettrage';
    protected static ?int $navigationSort=14;

    protected static ?string $navigationIcon = 'heroicon-o-viewfinder-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('libelle')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('arrondissement_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('libelle')
                    ->label("Nom du Quartier")
                    ->searchable(),
                Tables\Columns\TextColumn::make('arrondissement.libelle')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListQuartiers::route('/'),
            'create' => Pages\CreateQuartier::route('/create'),
            'view' => Pages\ViewQuartier::route('/{record}'),
            'edit' => Pages\EditQuartier::route('/{record}/edit'),
        ];
    }
}
