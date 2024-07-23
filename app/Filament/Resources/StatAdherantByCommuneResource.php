<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatAdherantByCommuneResource\Pages;
use App\Filament\Resources\StatAdherantByCommuneResource\RelationManagers;
use App\Models\Adherant;
use App\Models\Commune;
use App\Models\StatAdherantByCommune;
use App\Models\Statcommune;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class StatAdherantByCommuneResource extends Resource
{
    protected static ?string $model = Statcommune::class;

    protected static ?string $navigationGroup = 'Statistiques';
    protected static ?string $navigationLabel="Statistique par communes";
    protected static ?int $navigationSort=5;

    protected static ?string $navigationIcon = 'heroicon-o-globe-europe-africa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

//    protected function getTableQuery(): Builder
//    {
//        // Define the query to retrieve data
//        return Commune::query()->where('id',4);
//    }


    public static function table(Table $table): Table
    {


        $lisComm=Commune::all();

        foreach ($lisComm as $com){


//            if (Adherant::where('commune_id',$com->id)->count()!=0){
                Statcommune::firstOrCreate(
                    ['commune' => $com->libelle],
                    [
                        'commune'=>$com->libelle,
                        'total_adherant'=>Adherant::where('commune_id',$com->id)->count(),
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
            ->filters([


            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('Export')
                    ->label('Exporter PDF')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->openUrlInNewTab()
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records) {
                        return response()->streamDownload(function () use ($records) {

//                                dd($records);
                            echo Pdf::loadHTML(  Blade::render('stat_adherant_commune_pdf', ['records' => $records])
                            )->setPaper('A4', 'landscape')
                                ->stream();
                        }, 'Statistique_par_commune.pdf');
                    }),
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
            'index' => Pages\ListStatAdherantByCommunes::route('/'),
//            'create' => Pages\CreateStatAdherantByCommune::route('/create'),
//            'edit' => Pages\EditStatAdherantByCommune::route('/{record}/edit'),
        ];
    }
}
