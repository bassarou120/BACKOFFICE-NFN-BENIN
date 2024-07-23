<?php

use App\Http\Controllers\AdherantController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/inscription',[ AdherantController::class,'create'])->name('adherantInscription');
Route::post('/store',[ AdherantController::class,'store'])->name('adherantInscription');
Route::post('/check_email',[ AdherantController::class,'check_email'])->name('check_email');
Route::post('/getCommuneByDepartId',[ AdherantController::class,'getCommuneByDepartId'])->name('getCommuneByDepartId');
Route::post('/getArrondissementByCommId',[ AdherantController::class,'getArrondissementByCommId'])->name('getArrondissementByCommId');
Route::post('/getQuartierByArrondId',[ AdherantController::class,'getQuartierByArrondId'])->name('getQuartierByArrondId');

Route::get('/carte-membre', function () {
    return view('carte', [
        'nom' => 'Apollinaire Wilfrid AVOGNON',
        'titre' => 'Le Président',
        'numero_carte' => 'N° 01',
        'date_expiration' => '5 février 2031',
        'commune' => 'Cotonou',
        'arrondissement' => '13ème',
        'quartier' => 'Agla Hlazounto',
        'profession' => 'Statisticien',
        'telephone' => '+229 97717360',
        'photo' => '/path/to/photo.jpg' // Chemin de la photo
    ]);
});




Route::get('/generer_les_roles', function () {

    // Géner les CCE
    for ($i = 1; $i <= 24; $i++) {

        Role::firstOrCreate(['name'=>"CCE-".$i],['name'=>"CCE-".$i]);

        echo $i;
    }




    return redirect('/admin');
});
