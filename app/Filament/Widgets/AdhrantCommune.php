<?php

namespace App\Filament\Widgets;

use App\Models\Adherant;
use App\Models\Commune;
use App\Models\Statcommune;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Database\Eloquent\Builder;

class AdhrantCommune extends BaseWidget
{

    protected static ?string $heading =" Nombre d'adherants par Communes";
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';

//    protected static ?string $model = Commune::class;
//
//    public static function table(Table $table): Table
//    {
//
//
//        $lisComm=Commune::all();
//
//        foreach ($lisComm as $com){
//
//
////            if (Adherant::where('commune_id',$com->id)->count()!=0){
//            Statcommune::firstOrCreate(
//                ['commune' => $com->libelle],
//                [
//                    'commune'=>$com->libelle,
//                    'total_adherant'=>Adherant::where('commune_id',$com->id)->count(),
//                    'homme'=>Adherant::where('commune_id',$com->id)->where('genre',"MASCULIN")->count(),
//                    'femme'=>Adherant::where('commune_id',$com->id)->where('genre',"FEMININ")->count(),
//                    'cep'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"CEP")->count(),
//                    'bepc'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"BEPC")->count(),
//                    'bac'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"BAC")->count(),
//                    'licence'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"LICENCE")->count(),
//                    'master'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"MASTER")->count(),
//                    'doctorat'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"DOCTORAT")->count(),
//                    'autre'=>Adherant::where('commune_id',$com->id)->where('niveau_instruction',"AUTRE")->count()
//                ]
//            );
////            }
//
//        }
//
//
//
//        return $table
//            ->columns([
//                Tables\Columns\TextColumn::make('commune')
//                    ->label('Nom commune')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('total_adherant')
//                    ->label('Total adhérants')
//                    ->sortable()
//                    ->searchable(),
//
//                Tables\Columns\TextColumn::make('homme')
//                    ->label('Hommes')
//                    ->sortable()
//                    ->searchable(),
//
//                Tables\Columns\TextColumn::make('femme')
//                    ->label('Femmes')
//                    ->sortable()
//                    ->searchable(),
//
//                Tables\Columns\TextColumn::make('cep')
//                    ->label('Diplôme CEP')
//                    ->sortable()
//                    ->searchable(),
//
//
//                Tables\Columns\TextColumn::make('bepc')
//                    ->label('Diplôme BEPC')
//                    ->sortable()
//                    ->searchable(),
//
//
//                Tables\Columns\TextColumn::make('bac')
//                    ->label('Diplôme BAC')
//                    ->sortable()
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('licence')
//                    ->label('Licence')
//                    ->sortable()
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('master')
//                    ->label('Licence')
//                    ->sortable()
//                    ->searchable(),
//
//                Tables\Columns\TextColumn::make('doctorat')
//                    ->label('Autre Diplôme')
//                    ->sortable()
//                    ->searchable(),
//
//
//
////                Tables\Columns\TextColumn::make('adherants_count')
////                    ->label("Total adhérents")
////                    ->sortable()
////                    ->counts( 'adherants'),
//
////                Tables\Columns\TextColumn::make('adherants_count')
////                    ->label("Hommes ")
////                    ->counts( [
////                        'adherants' =>function (Builder $query) {
////
////                            $query-> where('genre', '=' ,"MASCULIN");
////                        },
////                    ])
////                    ->sortable()
//
//
//
//
////                Tables\Columns\TextColumn::make('masculin')
////                    ->label('Masculin')
////                    ->searchable()
////                    ->sortable(),
////                    ->counts([
////                        'adherants' =>function (Builder $query) {
////
////                            $query-> where('genre', "=","MASCULIN");
////                        },
////                    ]),
////                Tables\Columns\TextColumn::make('Feminin')
////                    ->label('Masculin')
////                    ->searchable()
////                    ->sortable()
////                    ->counts([
////                        'adherants' => fn (Builder $query) => $query->where('genre', "=","FEMININ"),
////                    ])
//
//
//            ])
////            ->filters([
////
////
////            ])
//            ->actions([
////                Tables\Actions\EditAction::make(),
//            ])
//            ->bulkActions([
////                Tables\Actions\BulkActionGroup::make([
////                    Tables\Actions\DeleteBulkAction::make(),
////                ]),
//            ]);
//    }


    public function table(Table $table): Table
    {




        return $table

            ->columns([
                Tables\Columns\TextColumn::make('commune')
                    ->label('Nom commune')
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
            ->query(
                Statcommune::query()
            );


    }
}
