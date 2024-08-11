<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Adherant;
use App\Models\Immo;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //define validation rules
        $validator = Validator::make($request->all(), [
            "type_transaction" => 'required',
            "identifiant" => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $a= Adherant::where('identifiant','=',$request->identifiant )->first() ;


        $b=Paiement::create([
            'adherant_id'=>$a->id,
            'type_transaction'=>$request->type_transaction,
            'ref_transaction'=>$request->ref_transaction,
            'montant'=>$request->montant,
            'status'=>"A CONFIRMER",

        ]);

        if ($request->type_transaction=='carte membre'){
            $a->can_receive_carte='OUI';
            $a->save();
        }



        return new PostResource(true, 'Votre paiement a été enregistre avec success',  $b);

    }

    /**
     * Display the specified resource.
     */
    public function show(Paiement $paiement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paiement $paiement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paiement $paiement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paiement $paiement)
    {
        //
    }
}
