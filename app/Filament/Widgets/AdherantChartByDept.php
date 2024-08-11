<?php

namespace App\Filament\Widgets;

use App\Models\Adherant;

use App\Models\Departement;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AdherantChartByDept extends ChartWidget
{
    protected static ?string $heading =" Nombre d'adherants par Departement";

    protected static ?int $sort = 1;

    protected static string $color = 'warning';

    protected function getData(): array
    {
        $dataDepatement = Departement::all();

        $libeleListe=[];
        $dataListe=[];
        foreach ( $dataDepatement as $dep ){

            array_push($libeleListe, $dep->libelle);

            array_push($dataListe,Adherant::where('departement_id',$dep->id)->where('status','=',"APPROUVER")->count());

        }

//         dd( $dataListe);

          return [
              'datasets' => [
                  [
                      'label' => "Nombre d'adherant par Departeemnt",
                      'data' => $dataListe,
                  ],
              ],
              'labels' => $libeleListe,
          ];

    }

    protected function getType(): string
    {
        return 'bar';
    }
}
