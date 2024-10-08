<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatAdherantByDepartemtnResource\Pages;
use App\Filament\Resources\StatAdherantByDepartemtnResource\RelationManagers;
use App\Models\Adherant;
use App\Models\Commune;
use App\Models\Departement;
use App\Models\RoleCommune;
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
use Illuminate\Support\Facades\Auth;
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
        Statdepartement:: query()->delete();
        $user = Auth::user();
        if($user->role != null && $user->role->name=="Administrateur"){
            $lisDep=Departement::all();
            foreach ($lisDep as $com){

                Statdepartement::firstOrCreate(
                    ['departement' => $com->libelle],
                    [
                        'departement'=>$com->libelle,
                        'total_adherant'=>Adherant::where('departement_id',$com->id)->where('status','=',"APPROUVER")->count(),
                        'homme'=>Adherant::where('departement_id',$com->id)->where('genre',"MASCULIN")->where('status','=',"APPROUVER")->count(),
                        'femme'=>Adherant::where('departement_id',$com->id)->where('genre',"FEMININ")->where('status','=',"APPROUVER")->count(),
                        'cep'=>Adherant::where('departement_id',$com->id)->where('niveau_instruction',"CEP")->where('status','=',"APPROUVER")->count(),
                        'bepc'=>Adherant::where('departement_id',$com->id)->where('niveau_instruction',"BEPC")->where('status','=',"APPROUVER")->count(),
                        'bac'=>Adherant::where('departement_id',$com->id)->where('niveau_instruction',"BAC")->where('status','=',"APPROUVER")->count(),
                        'licence'=>Adherant::where('departement_id',$com->id)->where('niveau_instruction',"LICENCE")->where('status','=',"APPROUVER")->count(),
                        'master'=>Adherant::where('departement_id',$com->id)->where('niveau_instruction',"MASTER")->where('status','=',"APPROUVER")->count(),
                        'doctorat'=>Adherant::where('departement_id',$com->id)->where('niveau_instruction',"DOCTORAT")->where('status','=',"APPROUVER")->count(),
                        'autre'=>Adherant::where('departement_id',$com->id)->where('niveau_instruction',"AUTRE")->where('status','=',"APPROUVER")->count()
                    ]
                );


            }

        }
        elseif (substr($user->role->name,0,3)=="CCE" || substr($user->role->name,0,3)=="CC-"){
            $roleCom=RoleCommune::where("role_id",'=',$user->role->id)->get();
            $listCom=[];
            foreach ($roleCom as $rc){array_push($listCom,$rc->commune_id);}

            $lisDep=Departement::all();
            foreach ($lisDep as $com){

//                dd($com);

                Statdepartement::firstOrCreate(
                    ['departement' => $com->libelle],
                    [
                        'departement'=>$com->libelle,
                        'total_adherant'=>Adherant::where('departement_id',$com->id) ->whereIn('commune_id',$listCom )->count(),
                        'homme'=>Adherant::where('departement_id',$com->id) ->whereIn('commune_id',$listCom )->where('genre',"MASCULIN")->where('status','=',"APPROUVER")->count(),
                        'femme'=>Adherant::where('departement_id',$com->id) ->whereIn('commune_id',$listCom )->where('genre',"FEMININ")->where('status','=',"APPROUVER")->count(),
                        'cep'=>Adherant::where('departement_id',$com->id) ->whereIn('commune_id',$listCom )->where('niveau_instruction',"CEP")->where('status','=',"APPROUVER")->count(),
                        'bepc'=>Adherant::where('departement_id',$com->id) ->whereIn('commune_id',$listCom )->where('niveau_instruction',"BEPC")->where('status','=',"APPROUVER")->count(),
                        'bac'=>Adherant::where('departement_id',$com->id) ->whereIn('commune_id',$listCom )->where('niveau_instruction',"BAC")->where('status','=',"APPROUVER")->count(),
                        'licence'=>Adherant::where('departement_id',$com->id) ->whereIn('commune_id',$listCom )->where('niveau_instruction',"LICENCE")->where('status','=',"APPROUVER")->count(),
                        'master'=>Adherant::where('departement_id',$com->id) ->whereIn('commune_id',$listCom )->where('niveau_instruction',"MASTER")->where('status','=',"APPROUVER")->count(),
                        'doctorat'=>Adherant::where('departement_id',$com->id) ->whereIn('commune_id',$listCom )->where('niveau_instruction',"DOCTORAT")->where('status','=',"APPROUVER")->count(),
                        'autre'=>Adherant::where('departement_id',$com->id) ->whereIn('commune_id',$listCom )->where('niveau_instruction',"AUTRE")->where('status','=',"APPROUVER")->count()
                    ]
                );


            }

        }
        elseif($user->role->name=="Réseau des Femmes"){}
        elseif ($user->role->name=="Réseau des Enseignants"){}
        elseif ($user->role->name=="Réseau des Elèves et Etudiants"){}
        elseif ($user->role->name=="Réseau des Artisans"){}


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
            ->modifyQueryUsing(function (\Illuminate\Contracts\Database\Eloquent\Builder $query) {
                return $query->where('total_adherant','!=',0)
                    ;

            })
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
