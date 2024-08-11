<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatAdherantByCommuneResource\Pages;
use App\Filament\Resources\StatAdherantByCommuneResource\RelationManagers;
use App\Models\Adherant;
use App\Models\Commune;
use App\Models\RoleCommune;
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
use Illuminate\Support\Facades\Auth;
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

        Statcommune:: query()->delete();
        $user = Auth::user();
        if($user->role->name=="Administrateur") {

            $lisComm = Commune::all();

            foreach ($lisComm as $com) {
                Statcommune::firstOrCreate(
                    ['commune' => $com->libelle],
                    [
                        'commune' => $com->libelle,
                        'total_adherant' => Adherant::where('commune_id', $com->id)->where('status','=',"APPROUVER")->count(),
                        'homme' => Adherant::where('commune_id', $com->id)->where('genre', "MASCULIN")->where('status','=',"APPROUVER")->count(),
                        'femme' => Adherant::where('commune_id', $com->id)->where('genre', "FEMININ")->where('status','=',"APPROUVER")->count(),
                        'cep' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "CEP")->where('status','=',"APPROUVER")->count(),
                        'bepc' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "BEPC")->where('status','=',"APPROUVER")->count(),
                        'bac' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "BAC")->where('status','=',"APPROUVER")->count(),
                        'licence' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "LICENCE")->where('status','=',"APPROUVER")->count(),
                        'master' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "MASTER")->where('status','=',"APPROUVER")->count(),
                        'doctorat' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "DOCTORAT")->where('status','=',"APPROUVER")->count(),
                        'autre' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "AUTRE")->where('status','=',"APPROUVER")->count()
                    ]
                );
            }
        }
        elseif (substr($user->role->name,0,3)=="CCE" || substr($user->role->name,0,3)=="CC-"){
            $roleCom=RoleCommune::where("role_id",'=',$user->role->id)->get();
            $listCom=[];
            foreach ($roleCom as $rc){array_push($listCom,$rc->commune_id);}


            $lisComm = Commune::whereIn("id",$listCom)->get();
//            dd($lisComm);

            foreach ($lisComm as $com) {
                Statcommune::firstOrCreate(
                    ['commune' => $com->libelle],
                    [
                        'commune' => $com->libelle,
                        'total_adherant' => Adherant::where('commune_id', $com->id)->count(),
                        'homme' => Adherant::where('commune_id', $com->id)->where('genre', "MASCULIN")->where('status','=',"APPROUVER")->count(),
                        'femme' => Adherant::where('commune_id', $com->id)->where('genre', "FEMININ")->where('status','=',"APPROUVER")->count(),
                        'cep' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "CEP")->where('status','=',"APPROUVER")->count(),
                        'bepc' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "BEPC")->where('status','=',"APPROUVER")->count(),
                        'bac' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "BAC")->where('status','=',"APPROUVER")->count(),
                        'licence' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "LICENCE")->where('status','=',"APPROUVER")->count(),
                        'master' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "MASTER")->where('status','=',"APPROUVER")->count(),
                        'doctorat' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "DOCTORAT")->where('status','=',"APPROUVER")->count(),
                        'autre' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "AUTRE")->where('status','=',"APPROUVER")->count()
                    ]
                );
            }

        }
        elseif($user->role->name=="Réseau des Femmes"){
            $lisComm = Commune::all();

            foreach ($lisComm as $com) {
                Statcommune::firstOrCreate(
                    ['commune' => $com->libelle],
                    [
                        'commune' => $com->libelle,
                        'total_adherant' => Adherant::where('commune_id', $com->id)->count(),
                        'homme' => Adherant::where('commune_id', $com->id)->where('genre', "MASCULIN")->where('status','=',"APPROUVER")->count(),
                        'femme' => Adherant::where('commune_id', $com->id)->where('genre', "FEMININ")->where('status','=',"APPROUVER")->count(),
                        'cep' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "CEP")->where('status','=',"APPROUVER")->count(),
                        'bepc' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "BEPC")->where('status','=',"APPROUVER")->count(),
                        'bac' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "BAC")->where('status','=',"APPROUVER")->count(),
                        'licence' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "LICENCE")->where('status','=',"APPROUVER")->count(),
                        'master' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "MASTER")->where('status','=',"APPROUVER")->count(),
                        'doctorat' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "DOCTORAT")->where('status','=',"APPROUVER")->count(),
                        'autre' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "AUTRE")->where('status','=',"APPROUVER")->count()
                    ]
                );
            }
        }
        elseif ($user->role->name=="Réseau des Enseignants"){
            $lisComm = Commune::all();

            foreach ($lisComm as $com) {
                Statcommune::firstOrCreate(
                    ['commune' => $com->libelle],
                    [
                        'commune' => $com->libelle,
                        'total_adherant' => Adherant::where('commune_id', $com->id)->count(),
                        'homme' => Adherant::where('commune_id', $com->id)->where('genre', "MASCULIN")->where('status','=',"APPROUVER")->count(),
                        'femme' => Adherant::where('commune_id', $com->id)->where('genre', "FEMININ")->where('status','=',"APPROUVER")->count(),
                        'cep' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "CEP")->where('status','=',"APPROUVER")->count(),
                        'bepc' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "BEPC")->where('status','=',"APPROUVER")->count(),
                        'bac' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "BAC")->where('status','=',"APPROUVER")->count(),
                        'licence' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "LICENCE")->where('status','=',"APPROUVER")->count(),
                        'master' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "MASTER")->where('status','=',"APPROUVER")->count(),
                        'doctorat' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "DOCTORAT")->where('status','=',"APPROUVER")->count(),
                        'autre' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "AUTRE")->where('status','=',"APPROUVER")->count()
                    ]
                );
            }
        }
        elseif ($user->role->name=="Réseau des Elèves et Etudiants"){
            $lisComm = Commune::all();

            foreach ($lisComm as $com) {
                Statcommune::firstOrCreate(
                    ['commune' => $com->libelle],
                    [
                        'commune' => $com->libelle,
                        'total_adherant' => Adherant::where('commune_id', $com->id)->count(),
                        'homme' => Adherant::where('commune_id', $com->id)->where('genre', "MASCULIN")->where('status','=',"APPROUVER")->count(),
                        'femme' => Adherant::where('commune_id', $com->id)->where('genre', "FEMININ")->where('status','=',"APPROUVER")->count(),
                        'cep' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "CEP")->where('status','=',"APPROUVER")->count(),
                        'bepc' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "BEPC")->where('status','=',"APPROUVER")->count(),
                        'bac' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "BAC")->where('status','=',"APPROUVER")->count(),
                        'licence' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "LICENCE")->where('status','=',"APPROUVER")->count(),
                        'master' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "MASTER")->where('status','=',"APPROUVER")->count(),
                        'doctorat' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "DOCTORAT")->where('status','=',"APPROUVER")->count(),
                        'autre' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "AUTRE")->where('status','=',"APPROUVER")->count()
                    ]
                );
            }
        }
        elseif ($user->role->name=="Réseau des Artisans"){
            $lisComm = Commune::all();

            foreach ($lisComm as $com) {
                Statcommune::firstOrCreate(
                    ['commune' => $com->libelle],
                    [
                        'commune' => $com->libelle,
                        'total_adherant' => Adherant::where('commune_id', $com->id)->count(),
                        'homme' => Adherant::where('commune_id', $com->id)->where('genre', "MASCULIN")->where('status','=',"APPROUVER")->count(),
                        'femme' => Adherant::where('commune_id', $com->id)->where('genre', "FEMININ")->where('status','=',"APPROUVER")->count(),
                        'cep' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "CEP")->where('status','=',"APPROUVER")->count(),
                        'bepc' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "BEPC")->where('status','=',"APPROUVER")->count(),
                        'bac' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "BAC")->where('status','=',"APPROUVER")->count(),
                        'licence' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "LICENCE")->where('status','=',"APPROUVER")->count(),
                        'master' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "MASTER")->where('status','=',"APPROUVER")->count(),
                        'doctorat' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "DOCTORAT")->where('status','=',"APPROUVER")->count(),
                        'autre' => Adherant::where('commune_id', $com->id)->where('niveau_instruction', "AUTRE")->where('status','=',"APPROUVER")->count()
                    ]
                );
            }
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
            ->modifyQueryUsing(function (\Illuminate\Contracts\Database\Eloquent\Builder $query) {
                return $query->where('total_adherant','!=',0)
                    ;

            })
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
