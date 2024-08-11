<?php


use App\Http\Controllers\AdherantController;
use App\Http\Controllers\PaiementController;
use App\Models\Adherant;
use App\Models\Commune;
use App\Models\Role;
use App\Models\RoleCommune;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;




Route::get('/confirmer_paiement', function ( ) {

    return view('confirmer_paiement' );
})->name('confirmer_paiement');
Route::post('/store_paiement',[  PaiementController::class,'store'])->name('store_paiement');


Route::get('/check_status/{identifiant?}', function ($identifiant=null) {

    return view('status', ['identifiant'=>$identifiant]);
})->name('check_status');




Route::post('/check_status_id',[ AdherantController::class,'check_status_id'])->name('check_status_id');

Route::post('/check_num_id',[ AdherantController::class,'check_num_id'])->name('check_num_id');



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

       $rol= Role::firstOrCreate(['name'=>"CCE-".$i],['name'=>"CCE-".$i]);


       User::firstOrCreate(
           [
               'name'=>"CCE-".$i,
               'email'=>'cce-'.$i.'@nfn.bj',
               'role_id'=>$rol->id,
               'password'=>Hash::make('CirElectNFN2024@'),
           ],
           [
               'name'=>"CCE-".$i,
               'email'=>'cce-'.$i.'@nfn.bj',
               'role_id'=>$rol->id,
               'password'=>Hash::make('CirElectNFN2024@'),
           ]);



        echo $i;
    }

    $listCommunes= Commune::all();


    foreach ($listCommunes as $com){
      $rol=  Role::firstOrCreate(['name'=>"CC-".$com->libelle],['name'=>"CC-".$com->libelle]);
        RoleCommune::firstOrCreate(['role_id'=>$rol->id ,'commune_id'=>$com->id],['role_id'=>$rol->id ,'commune_id'=>$com->id]);

    }






    return redirect('/admin');
});

Route::get('/generer_id',function (){

 $adherants=\App\Models\Adherant::all();

// dd(sizeof($adherants));

     foreach ($adherants as $adherant){

         $com=Commune::find($adherant->commune_id);
         $rc=RoleCommune::where('commune_id',$adherant->commune_id)->get();
         $a='';
         $listUseCCE=[];
         foreach ($rc as $r){

             if( $r->role!=null && substr($r->role->name,0,3)=="CCE" ){
                 $a= substr($r->role->name,4)   ;
                 $listUseCCE=$r->role->users;
             }
         }


         $adherant->identifiant=   str_pad($a, 2, '0', STR_PAD_LEFT)
             .strtoupper(substr($com->libelle, 0, 3))
             .str_pad($adherant->id, 5, '0', STR_PAD_LEFT);
          $adherant->save();
     }




    return "ok";
});

