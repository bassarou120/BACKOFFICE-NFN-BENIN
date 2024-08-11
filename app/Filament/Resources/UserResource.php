<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Commune;
use App\Models\Role;
use App\Models\RoleCommune;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Gestion des Utilisateurs';

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort=8;

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(),


                Forms\Components\Select::make('role_id')
                    ->label('Role')
                    //  ->relationship('commune','libelle')
                    ->options(fn (Get $get): Collection => Role::query()

                        ->pluck("name","id")
                    )
                    ->afterStateUpdated( function (Set $set){

                        $set('arrondissement_id',null);
                        $set('quartier_id',null);

                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                ,
                Forms\Components\TextInput::make('password')
                    ->required()
                    ->password()
                    ->maxLength(255)
                    ->rule(Password::default())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('name')
            ->sortable()
            ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('Role')
                    ->sortable()
//                    ->searchable()
                    ->getStateUsing(function ($record) {
//                        dd($record->role_id);
//                        $rol=Role::find($record->role_id);
                        $listComme=Role::find($record->role_id)->name;
                        return  $listComme;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de creation')
                    ->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
        ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),

                Action::make('changePassword')
                    ->action(function (User $record, array $data): void {
                        $record->update([
                            'password' => Hash::make($data['new_password']),
                        ]);

                        Filament::notify('success', 'Password changed successfully.');
                    })
                    ->form([
                        Forms\Components\TextInput::make('new_password')
                            ->password()
                            ->label('New Password')
                            ->required()
                            ->rule(Password::default()),
                        Forms\Components\TextInput::make('new_password_confirmation')
                            ->password()
                            ->label('Confirm New Password')
                            ->rule('required', fn($get) => ! ! $get('new_password'))
                            ->same('new_password'),
                    ])
                    ->icon('heroicon-o-key')
//                    ->visible(fn (User $record): bool => $record->role->name == Role::ROLE_ADMINISTRATOR),
                    ->visible(fn (User $record): bool => $record->role->name == Role::ROLE_ADMINISTRATOR),
                Action::make('deactivate')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->action(fn (User $record) => $record->delete())
                    ->visible(fn (User $record): bool => $record->role->name == Role::ROLE_ADMINISTRATOR),


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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
//            'edit' => Pages\EditUser::route('/{record}/edit'),
            'sort' => Pages\SortUsers::route('/sort'),
        ];
    }
}
