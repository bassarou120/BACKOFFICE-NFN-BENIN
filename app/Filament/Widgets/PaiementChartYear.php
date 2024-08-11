<?php

namespace App\Filament\Widgets;

use App\Models\Adherant;

use App\Models\Paiement;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Auth;

class PaiementChartYear extends ChartWidget
{
    protected static ?string $heading = "Evolution des paiement dans l'année";

    protected static ?int $sort = 4;

    protected static string $color = 'success';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {

        $user = Auth::user();
        $currentYear = Carbon::now()->year;
        if($user->role->name=="Administrateur"   ){

            $data = Trend::model(Paiement::class)
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )
                ->perMonth()
                ->sum('montant');

            return [
                'datasets' => [
                    [
                        'label' => 'Paiements',
                        'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    ],
                ],
                'labels' => ['Jan', 'Feb', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aout', 'Sep', 'Oct', 'Nov', 'Dec'],
//            'labels' => $data->map(fn (TrendValue $value) => $value->date),
            ];
        }else{

            return [];
        }



    }

    protected function getType(): string
    {
        return 'line';
    }
}
