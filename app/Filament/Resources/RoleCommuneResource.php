<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleCommuneResource\Pages;
use App\Filament\Resources\RoleCommuneResource\RelationManagers;
use App\Models\Commune;
use App\Models\RoleCommune;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleCommuneResource extends Resource
{
    protected static ?string $model = RoleCommune::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Gestion des Utilisateurs';

    protected static ?int $navigationSort=10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('role_id')
                    ->required()
                    ->relationship('role','name')
                    ->searchable()
                    ->preload() ,
                Forms\Components\MultiSelect::make('commune_id')
                    ->label('Communes')
                    ->options(fn () => Commune::all()->pluck('libelle', 'id'))
                    ->required()
                    ->searchable()
                    ->preload(),
//                Forms\Components\Select::make('commune')
//                    ->multiple()
//                    ->required()
//                    ->relationship('commune','libelle')
//                    ->searchable()
//                    ->preload() ,
            ]);
    }

    public function save()
    {

        dd("je suis la ");
        $data = $this->form->getState();

        $roleCommune = RoleCommune::create([
            'role_id' => $data['role_id'],
        ]);

        $roleCommune->communes()->sync($data['commune_ids']);

        // Redirection ou autre action après création
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('role.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('commune.libelle')

                    ->sortable(),
//                Tables\Columns\TextColumn::make('commune_id')
//                    ->numeric()
//                    ->sortable(),
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
            'index' => Pages\ListRoleCommunes::route('/'),
            'create' => Pages\CreateRoleCommune::route('/create'),
            'edit' => Pages\EditRoleCommune::route('/{record}/edit'),
        ];
    }
}
