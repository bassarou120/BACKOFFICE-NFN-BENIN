<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommuneRessourceResource\RelationManagers\RolesRelationManager;
use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Commune;
use App\Models\Role;
use App\Models\RoleCommune;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationGroup = 'Gestion des Utilisateurs';
    protected static ?int $navigationSort=9;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

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

                Tables\Columns\TextColumn::make('name')
                    ->label('Role')
                    ->sortable()
                    ->searchable(),


                Tables\Columns\TextColumn::make('communes')
                    ->getStateUsing(function ($record) {

//                        $rol=Role::find($record->id);

                        $listComme="";
                        $rols=   RoleCommune::where('role_id',$record->id)->get();

                        foreach ($rols as $r){
                            $listComme= $listComme .",". Commune::find($r->commune_id)->libelle;

                        }


                        return  $listComme;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de creation')
                    ->dateTime(),

//                Tables\Columns\TextColumn::make('communes')
//                    ->label('Commune')
//                    ->formatStateUsing(fn($state) => implode(', ', $state))
//                    ->sortable()
//                    ->searchable(),


//                Tables\Columns\TextColumn::make('communes')
//                    ->label('Date de creation')
//                    ->dateTime(),


            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
//
//    public static function getRelations(): array
//    {
//        return [
//            RelationManagers\TagsRelationManager::class,
//        ];
//    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
