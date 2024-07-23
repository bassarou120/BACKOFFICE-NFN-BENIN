<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatAdherantByQuartierResource\Pages;
use App\Filament\Resources\StatAdherantByQuartierResource\RelationManagers;
use App\Models\Adherant;
use App\Models\Departement;
use App\Models\Quartier;
use App\Models\StatAdherantByQuartier;
use App\Models\Statdepartement;
use App\Models\Statquartier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatAdherantByQuartierResource extends Resource
{
    protected static ?string $model = Statquartier::class;

    protected static ?string $navigationGroup = 'Statistiques';
    protected static ?string $navigationLabel="Statistique par Quartier";
    protected static ?string $navigationIcon = 'heroicon-o-eye-dropper';
    protected static ?int $navigationSort=7;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {

        $lisDep=Quartier::all();

        foreach ($lisDep as $com){

//            if (Adherant::where('commune_id',$com->id)->count()!=0){
            Statquartier::firstOrCreate(
                ['quartier' => $com->libelle],
                [
                    'quartier'=>$com->libelle,
                    'total_adherant'=>Adherant::where('quartier_id',$com->id)->count(),
                    'homme'=>Adherant::where('commune_id',$com->id)->where('genre',"MASCULIN")->count(),
                    'femme'=>Adherant::where('commune_id',$com->id)->where('genre',"FEMININ")->count(),
                    'cep'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"CEP")->count(),
                    'bepc'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"BEPC")->count(),
                    'bac'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"BAC")->count(),
                    'licence'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"LICENCE")->count(),
                    'master'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"MASTER")->count(),
                    'doctorat'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"DOCTORAT")->count(),
                    'autre'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"AUTRE")->count()
                ]
            );
//            }

        }
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quartier')
                    ->label('Nom Quartier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_adherant')
                    ->label('Total adhérants')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('homme')
                    ->label('Hommes')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('femme')
                    ->label('Femmes')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('cep')
                    ->label('Diplôme CEP')
                    ->sortable()
                    ->searchable(),


                Tables\Columns\TextColumn::make('bepc')
                    ->label('Diplôme BEPC')
                    ->sortable()
                    ->searchable(),


                Tables\Columns\TextColumn::make('bac')
                    ->label('Diplôme BAC')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('licence')
                    ->label('Licence')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('master')
                    ->label('Licence')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('doctorat')
                    ->label('Autre Diplôme')
                    ->sortable()
                    ->searchable(),



//                Tables\Columns\TextColumn::make('adherants_count')
//                    ->label("Total adhérents")
//                    ->sortable()
//                    ->counts( 'adherants'),

//                Tables\Columns\TextColumn::make('adherants_count')
//                    ->label("Hommes ")
//                    ->counts( [
//                        'adherants' =>function (Builder $query) {
//
//                            $query-> where('genre', '=' ,"MASCULIN");
//                        },
//                    ])
//                    ->sortable()




//                Tables\Columns\TextColumn::make('masculin')
//                    ->label('Masculin')
//                    ->searchable()
//                    ->sortable(),
//                    ->counts([
//                        'adherants' =>function (Builder $query) {
//
//                            $query-> where('genre', "=","MASCULIN");
//                        },
//                    ]),
//                Tables\Columns\TextColumn::make('Feminin')
//                    ->label('Masculin')
//                    ->searchable()
//                    ->sortable()
//                    ->counts([
//                        'adherants' => fn (Builder $query) => $query->where('genre', "=","FEMININ"),
//                    ])


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
            'index' => Pages\ListStatAdherantByQuartiers::route('/'),
            'create' => Pages\CreateStatAdherantByQuartier::route('/create'),
            'edit' => Pages\EditStatAdherantByQuartier::route('/{record}/edit'),
        ];
    }
}
