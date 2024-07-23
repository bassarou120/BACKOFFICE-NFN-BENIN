<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Mail\SampleMail2;
use App\Models\Adherant;
use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\Departement;
use App\Models\Quartier;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class AdherantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function check_email(Request $request){

       $a= Adherant::where('email',$request->email )->get();

        if ($a){
            return "oui";
        }else{
            return "non";
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        $listeDepartemnt=Departement::all();

//        dd($listeDepartemnt);

        return view('welcome',compact('listeDepartemnt'));
    }


    public function  getCommuneByDepartId(Request $request){

        $data['communes']=Commune::where("departement_id", $request->departement_id)
            ->get(["libelle", "id"]);
        return response()->json($data);
     }

   public function  getArrondissementByCommId(Request $request){

       $data['arrondissements']=Arrondissement::where("commune_id", $request->commune_id)
            ->get(["libelle", "id"]);
        return response()->json($data);
     }


  public function  getQuartierByArrondId(Request $request){

       $data['quartiers']=Quartier::where("arrondissement_id", $request->arrondissement_id)
            ->get(["libelle", "id"]);
        return response()->json($data);
     }



    /**
     * Store a newly created resource in storage.
     *  $table->foreignId("departement_id")->constrained()->cascadeOnDelete();
    $table->foreignId("commune_id")->constrained()->cascadeOnDelete();
    $table->foreignId("arrondissement_id")->constrained()->cascadeOnDelete();
    $table->foreignId("quartier_id")->constrained()->cascadeOnDelete();
    $table->string("nom");
    $table->string("prenom");
    $table->date("date_naissance");
    $table->string("lieu_residence");
    $table->string("adresse");
    $table->string("telephone");
    $table->string("email");

    $table->string("photo_identite");
    $table->string("piece_photo_identite");
    $table->string("niveau_instruction");
    $table->string("activite_profession");
    $table->text("ambition_politique");
    $table->string("status")->default('EN ATTENTE DE VALIDATION');
     */

    public function store(Request $request,$id=null)
    {



        try {

            $adherant=Adherant::updateOrCreate(
                ['id'=>$id],
                [
                    'nom'=>$request->nom,
                    'prenom'=>$request->prenom,
                    'date_naissance'=>$request->date_naissance,
                    'lieu_residence'=>$request->adresse,
                    'adresse'=>$request->adresse,
                    'telephone'=>$request->telephone,
                    'email'=>$request->email,
                    'niveau_instruction'=>$request->niveau_instruction,
                    'activite_profession'=>$request->activite_profession,
                    'ambition_politique'=>$request->ambition_politique,
                    'departement_id'=>$request->departement_id,
                    'commune_id'=>$request->commune_id,
                    'arrondissement_id'=>$request->arrondissement_id,
                    'quartier_id'=>$request->quartier_id,
                    'genre'=>$request->genre,
                    'photo_identite'=>"-",
                    'piece_photo_identite'=>"-",
                    'categorie_socio'=>$request->categorie_socio

                ]);

            $path_photo_perso='/adherant_photo/'.$adherant->id.'/';
            $photo_perso=$request->file('photo_perso')? $request->file('photo_perso'):null ;




            $photo_persoName='';
            if ($photo_perso!=null){
                $photo_persoName = time() . '.' . $photo_perso->getClientOriginalExtension();
                $adherant->photo_identite=$path_photo_perso. $photo_persoName ;
                $adherant  ->save();
            }

            if($request->file('photo_perso')){
                $image = $request->file('photo_perso');
                $image->move(storage_path('/app/public'.$path_photo_perso), $photo_persoName);

            }



            $path_photo_id='/adherant_photo_id/'.$adherant->id.'/';
            $photo_id=$request->file('photo_id')? $request->file('photo_id'):null ;
            $photo_idName='';

            if ($photo_id!=null){
                $photo_idName = time() . '.' . $photo_id->getClientOriginalExtension();
                $adherant->piece_photo_identite=$path_photo_id. $photo_idName ;
                $adherant  ->save();
            }

            if($request->file('photo_id')){
                $image = $request->file('photo_id');
                $image->move(storage_path('/app/public'.$path_photo_id), $photo_idName);

            }

            $content = [
                'subject' => 'Confirmation de votre adhésion au parti FNF',
                'body' =>"Cher(e)". $request->prenom." ".  $request->nom." ,<br>
            Nous avons bien reçu votre demande d'adhésion au parti NFN via notre formulaire en ligne.<br>

            Votre adhésion a été enregistrée avec succès. Un membre de notre administration va procéder à la vérification des informations que vous avez fournies. Une fois cette vérification terminée, votre adhésion sera validée et nous mettrons à votre disposition votre carte de membre.
            <br>
            Nous vous remercions pour votre confiance et votre engagement envers le parti NFN. Votre participation est essentielle pour nous aider à atteindre nos objectifs communs et à promouvoir nos valeurs.
            <br>
            Nous restons à votre disposition pour toute question ou information supplémentaire.<br>


                Cordialement,


                 <br>»
                "
            ];

            Mail::to([$request->email ])->send(new SampleMail2($content));






            return new PostResource(true, 'Votre a soumission a ete enregistre avec success', $adherant);



        }catch (\Exception $e){
            return new PostResource(false,  $e->getMessage(), '');


        }

//        dd($request->photo_perso->file);


    }

    /**
     * Display the specified resource.
     */
    public function show(Adherant $adherant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adherant $adherant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adherant $adherant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adherant $adherant)
    {
        //
    }
}
