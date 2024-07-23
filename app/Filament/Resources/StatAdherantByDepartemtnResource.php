<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatAdherantByDepartemtnResource\Pages;
use App\Filament\Resources\StatAdherantByDepartemtnResource\RelationManagers;
use App\Models\Adherant;
use App\Models\Commune;
use App\Models\Departement;
use App\Models\StatAdherantByDepartemtn;
use App\Models\Statcommune;
use App\Models\Statdepartement;
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

class StatAdherantByDepartemtnResource extends Resource
{
    protected static ?string $model = Statdepartement::class;

    protected static ?string $navigationGroup = 'Statistiques';
    protected static ?string $navigationLabel="Statistique par Departement";
    protected static ?int $navigationSort=4;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {


        $lisDep=Departement::all();

        foreach ($lisDep as $com){

//            if (Adherant::where('commune_id',$com->id)->count()!=0){
            Statdepartement::firstOrCreate(
                ['departement' => $com->libelle],
                [
                    'departement'=>$com->libelle,
                    'total_adherant'=>Adherant::where('departement_id',$com->id)->count(),
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
                Tables\Columns\TextColumn::make('departement')
                    ->label('Nom departement')
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
                    ->label('Master')
                    ->sortable()
                    ->searchable(),


                Tables\Columns\TextColumn::make('doctorat')
                    ->label('Doctorat')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('autre')
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

                Tables\Actions\BulkAction::make('Export')
                    ->label('Exporter PDF')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->openUrlInNewTab()
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records) {
                        return response()->streamDownload(function () use ($records) {

//                                dd($records);
                            echo Pdf::loadHTML(  Blade::render('stat_adherant_departement_pdf', ['records' => $records])
                            )->setPaper('A4', 'landscape')
                                ->stream();
                        }, 'Statistique_par_départements.pdf');
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
            'index' => Pages\ListStatAdherantByDepartemtns::route('/'),
            'create' => Pages\CreateStatAdherantByDepartemtn::route('/create'),
            'edit' => Pages\EditStatAdherantByDepartemtn::route('/{record}/edit'),
        ];
    }
}
