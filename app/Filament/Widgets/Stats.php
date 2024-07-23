<?php

namespace App\Filament\Widgets;

use App\Models\Adherant;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Stats extends BaseWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Membres', Adherant::query()->count())
                ->description('Tous les adhérents du NFN')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Utilisateurs', User::query()->count())
                ->description('Les administrateur du NFN')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Total de payements', '192.1k'),
//            Stat::make('Employees', Employee::query()->count())
//                ->description('All employees from the database')
//                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->color('success'),
        ];
    }
}
