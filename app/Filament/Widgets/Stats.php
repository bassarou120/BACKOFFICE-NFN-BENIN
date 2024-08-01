<?php

namespace App\Filament\Widgets;

use App\Models\Adherant;
use App\Models\RoleCommune;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class Stats extends BaseWidget
{
    protected function getStats(): array
    {

        $user = Auth::user();

        if($user->role->name=="Administrateur"   ){
            return [

                Stat::make('Membres', Adherant::query()->where('status','=',"APPROUVER")->count())
                    ->description('Tous les adhérents du NFN')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->chart([7, 2, 10, 3, 15, 4, 17])
                    ->color('success'),
                Stat::make('Membres en Attente', Adherant::query()->where('status','!=',"APPROUVER")->count())
                    ->description('Les membres en attente de validation')
                    ->descriptionIcon('heroicon-m-arrow-trending-down')
                    ->color('danger'),

                Stat::make('Total de payements', '192.1k'),
//            Stat::make('Employees', Employee::query()->count())
//                ->description('All employees from the database')
//                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->color('success'),
            ];
        }
        elseif (substr($user->role->name,0,3)=="CCE" || substr($user->role->name,0,3)=="CC-") {
            $roleCom = RoleCommune::where("role_id", '=', $user->role->id)->get();
            $listCom = [];
            foreach ($roleCom as $rc) {
                array_push($listCom, $rc->commune_id);
            }
            return [

                Stat::make('Membres', Adherant::query()->where('status','=',"APPROUVER")->whereIn('commune_id',$listCom)->count())
                    ->description('Tous les adhérents du NFN')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->chart([7, 2, 10, 3, 15, 4, 17])
                    ->color('success'),
                Stat::make('Membres en Attente', Adherant::query()->where('status','!=',"APPROUVER")->whereIn('commune_id',$listCom) ->count())
                    ->description('Les membres en attente de validation')
                    ->descriptionIcon('heroicon-m-arrow-trending-down')
                    ->color('danger'),

                Stat::make('Total de payements', '192.1k'),
//            Stat::make('Employees', Employee::query()->count())
//                ->description('All employees from the database')
//                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->color('success'),
            ];

        }
         elseif($user->role->name=="Réseau des Femmes"){
             return [

                 Stat::make('Membres', Adherant::query() ->where('genre','=',"FEMININ")->where('status','=',"APPROUVER")->count())
                     ->description('Tous les adhérents du NFN')
                     ->descriptionIcon('heroicon-m-arrow-trending-up')
                     ->chart([7, 2, 10, 3, 15, 4, 17])
                     ->color('success'),
                 Stat::make('Membres en Attente', Adherant::query() ->where('genre','=',"FEMININ")->where('status','!=',"APPROUVER")->count())
                     ->description('Les membres en attente de validation')
                     ->descriptionIcon('heroicon-m-arrow-trending-down')
                     ->color('danger'),

                 Stat::make('Total de payements', '192.1k'),
//            Stat::make('Employees', Employee::query()->count())
//                ->description('All employees from the database')
//                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->color('success'),
             ];

         }
        elseif ($user->role->name=="Réseau des Enseignants"){
            return [

                Stat::make('Membres', Adherant::query() ->where('categorie_socio','=',"Enseignants")->where('status','=',"APPROUVER")->count())
                    ->description('Tous les adhérents du NFN')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->chart([7, 2, 10, 3, 15, 4, 17])
                    ->color('success'),
                Stat::make('Membres en Attente', Adherant::query() ->where('categorie_socio','=',"Enseignants")->where('status','!=',"APPROUVER")->count())
                    ->description('Les membres en attente de validation')
                    ->descriptionIcon('heroicon-m-arrow-trending-down')
                    ->color('danger'),

                Stat::make('Total de payements', '192.1k'),
//            Stat::make('Employees', Employee::query()->count())
//                ->description('All employees from the database')
//                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->color('success'),
            ];
        }
        elseif ($user->role->name=="Réseau des Elèves et Etudiants"){
            return [

                Stat::make('Membres', Adherant::query()
                    ->where('categorie_socio','=',"Etudiants")
                    ->orWhere('categorie_socio','=',"Elèves")
                    ->where('status','=',"APPROUVER")->count())
                    ->description('Tous les adhérents du NFN')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->chart([7, 2, 10, 3, 15, 4, 17])
                    ->color('success'),
                Stat::make('Membres en Attente', Adherant::query()
                    ->where('categorie_socio','=',"Etudiants")
                    ->orWhere('categorie_socio','=',"Elèves")
                    ->where('status','!=',"APPROUVER")->count())
                    ->description('Les membres en attente de validation')
                    ->descriptionIcon('heroicon-m-arrow-trending-down')
                    ->color('danger'),

                Stat::make('Total de payements', '192.1k'),
//            Stat::make('Employees', Employee::query()->count())
//                ->description('All employees from the database')
//                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->color('success'),
            ];
        }
        elseif ($user->role->name=="Réseau des Artisans"){
            return [

                Stat::make('Membres', Adherant::query()
                    ->where('categorie_socio','=',"Artisans")
                    ->where('status','=',"APPROUVER")->count())
                    ->description('Tous les adhérents du NFN')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->chart([7, 2, 10, 3, 15, 4, 17])
                    ->color('success'),
                Stat::make('Membres en Attente', Adherant::query()
                    ->where('categorie_socio','=',"Artisans")
                    ->where('status','!=',"APPROUVER")->count())
                    ->description('Les membres en attente de validation')
                    ->descriptionIcon('heroicon-m-arrow-trending-down')
                    ->color('danger'),

                Stat::make('Total de payements', '192.1k'),
//            Stat::make('Employees', Employee::query()->count())
//                ->description('All employees from the database')
//                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->color('success'),
            ];
        }

        return [];

    }
}
