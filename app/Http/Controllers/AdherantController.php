<?php

namespace App\Http\Controllers;


use App\Http\Resources\PostResource;
use App\Mail\SampleMail2;
use App\Models\Adherant;
use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\Departement;
use App\Models\Quartier;
use App\Models\RoleCommune;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class AdherantController extends Controller
{

    public $numCirconsciprtion='';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }





    public function check_num_id(Request $request){
        $a= Adherant::where('identifiant','=',$request->identifiant )->first() ;

        if ($a ==null ){
            return new PostResource(true, "Numéro d'adherent non valide",null);

        }else{
            return new PostResource(true, 'valide', 'valide' );
        }


    }
    public function check_status_id(Request $request){


        $a= Adherant::where('identifiant','=',$request->identifiant )->first() ;


        if ($a ==null ){

            return new PostResource(true, "Ce numero d'adhession n'est pas valide","num");
        }
        else{

            return new PostResource(true, $a->status,$a);

        }

    }


    public function check_email(Request $request){

       $a= Adherant::where('email','=',$request->email )->get();

        if (sizeof($a)==0){
            return new PostResource(true, "Ok","non");

        }else{
            return new PostResource(true, 'email existe dejà', 'oui' );
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
                    'categorie_socio'=>$request->categorie_socio,
                    'status'=>'EN ATTENTE DE VALIDATION'

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



            $com=Commune::find($request->commune_id);
            $rc=RoleCommune::where('commune_id',$request->commune_id)->get();
            $numCirconsciprtin='';
            foreach ($rc as $r){

                if( $r->role!=null && substr($r->role->name,0,3)=="CCE" ){
                    $numCirconsciprtin= substr($r->role->name,4)   ;
                }
            }

            $identifiant= str_pad($numCirconsciprtin, 2, '0', STR_PAD_LEFT)
                .strtoupper(substr($com->libelle, 0, 3))
                .str_pad($adherant->id, 5, '0', STR_PAD_LEFT);


            $adherant->identifiant=$identifiant;
            $adherant->save();


            $url=route('check_status');
            $contentAdminAdherant = [
                'subject' => 'Confirmation de votre adhésion au parti FNF',
                'body' =>"Cher(e)". $request->prenom." ".  $request->nom." ,<br>
             Bravo, Vous venez de soumettre avec succès votre demande d’adhésion à la NFN. <br>
             Pour connaître le niveau du traitement de votre dossier d’adhésion, cliquez <a href='".$url."/".$identifiant."'>ICI</a>   <br>
              et taper votre numéro d’adhésion  <b> :".$identifiant. " </b> ou contactez le +22990430506 (appel et WhatsApp)
                 <br>
                "
            ];

            $users = User::with('role')->get();
             $listAdminAdherant =[];
             $listAdminOnly  =[];
            array_push($listAdminAdherant,$request->email);


            foreach ($users as  $user){

                if ($user->role !=null  && $user->role->name=="Administrateur"){
                    array_push($listAdminAdherant,$user->email);
                    array_push($listAdminOnly,$user->email);
                }

            }

            Mail::to($listAdminAdherant)->send(new SampleMail2($contentAdminAdherant));

            $contentCCE= [
                'subject' => 'Confirmation de votre adhésion au parti FNF',
                'body' =>"Demande d’adhésion N°: ".$identifiant. " enregistrée. <br>
                        Votre pré-validation est requise dans un délai de 72h.
                 <br>
                "
            ];


            $this->numCirconsciprtion=$numCirconsciprtin;
            $users1 =  User::with(['role'=>function($re){
                return $re->where('name',"CCE-". $this->numCirconsciprtion);

            }])->get();


            $listAdminCCE=[];



                        foreach ($users1 as  $user){
                            if ($user->role !=null ){
                                array_push($listAdminCCE,$user->email);
                            }
                        }


                        if (sizeof($listAdminCCE)==0){
                            Mail::to($listAdminOnly)->send(new SampleMail2($contentCCE));
                        }else{
                            Mail::to($listAdminCCE)->send(new SampleMail2($contentCCE));
                        }




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
