<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaiementResource\Pages;
use App\Filament\Resources\PaiementResource\RelationManagers;
use App\Models\Paiement;
use App\Models\RoleCommune;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PaiementResource extends Resource
{
    protected static ?string $model = Paiement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('adherant_id')
                    ->relationship('adherant', 'nom')
                    ->required(),


                Forms\Components\TextInput::make('type_transaction')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ref_transaction')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('montant')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('adherant.nom')
                    ->label('Nom')
                   ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('adherant.prenom')
                    ->label('Prénom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type_transaction')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('ref_transaction')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('montant')
                    ->sortable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('adherant.commune.libelle')
                    ->label('Commune')
                    ->searchable()
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

                Tables\Filters\SelectFilter::make("Adherant")
                    ->preload()
                    ->searchable()
                    ->relationship('adherant','nom'),

                Tables\Filters\SelectFilter::make("Commune")
                    ->preload()
                    ->searchable()
                    ->relationship('adherant.commune','libelle'),

                Filter::make('created_at')
                    ->label("Periode")
                    ->form([
                        DatePicker::make('created_from')->label('Date debut'),
                        DatePicker::make('created_until')->label('Date fin'),
                    ])
                    ->query(function (\Illuminate\Contracts\Database\Eloquent\Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Date debut ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Date fin ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    })->columnSpan(2)->columns(2),


            ],     layout:FiltersLayout:: AboveContentCollapsible)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (\Illuminate\Contracts\Database\Eloquent\Builder $query) {


//                dd($query);

                $user = Auth::user();
                if($user->role->name=="Administrateur"){
                    $req=$query ;
                }elseif($user->role->name=="Réseau des Femmes"){
                 $req = $query->whereHas('adherant', function($r) {
                    $r->where('genre','=',"FEMININ");
                });

                }elseif ($user->role->name=="Réseau des Enseignants")
                {

                    $req = $query->whereHas('adherant', function($r) {
                        $r ->where('categorie_socio','=',"Enseignants");
                    });

                }elseif ($user->role->name=="Réseau des Elèves et Etudiants"){

                    $req = $query->whereHas('adherant', function($r) {
                        $r  ->where('categorie_socio','=',"Etudiants")
                            ->orWhere('categorie_socio','=',"Elèves");
                    });


                }elseif ($user->role->name=="Réseau des Artisans"){

                    $req = $query->whereHas('adherant', function($r) {
                        $r  ->where('categorie_socio','=',"Artisans");
                    });

                } elseif (substr($user->role->name,0,3)=="CCE" || substr($user->role->name,0,3)=="CC-"){

                    $roleCom=RoleCommune::where("role_id",'=',$user->role->id)->get();
                    $listCom=[];
                    foreach ($roleCom as $rc){array_push($listCom,$rc->commune_id);}

//                    $req= $query->where('status','=',"APPROUVER")
//                        ->whereIn('commune_id',$listCom );


                    $req = $query->whereHas('adherant', function($r) use ($listCom) {
                        $r   ->whereIn('commune_id',$listCom );
                    });
                }



//                $req = $query->whereHas('adherant', function($r) {
//                    $r->where('id', '=', 97);
//                });
//                $req=$query-> with(['adherant'=>function($r){
//                    return  $r->where("id",'=',90) ;
//                }]);


                return $req;

            });
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
            'index' => Pages\ListPaiements::route('/'),
            'create' => Pages\CreatePaiement::route('/create'),
            'view' => Pages\ViewPaiement::route('/{record}'),
            'edit' => Pages\EditPaiement::route('/{record}/edit'),
        ];
    }
}
