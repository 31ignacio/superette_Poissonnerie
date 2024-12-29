<?php

namespace App\Http\Controllers;

use App\Http\Requests\saveFournisseurRequest;
use App\Models\Fournisseur;
use App\Models\fournisseurInfo;
use Exception;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    //

    public function index()
    {
        $fournisseurs = Fournisseur::all();
        //dd($fournisseurs);

        return view('Fournisseurs.index', compact('fournisseurs'));
    }

    public function detail($fournisseur)
    {
        //dd($fournisseur);

        $fournisseurInfo = fournisseurInfo::where('fournisseur_id', $fournisseur)->orderBy('date', 'desc')->paginate(5);
        
    
        $achats = FournisseurInfo::where('fournisseur_id', $fournisseur)
        ->where('status', 'Achat')
        ->orderBy('date', 'desc')
        ->paginate(8);

        $reglements = FournisseurInfo::where('fournisseur_id', $fournisseur)
        ->where('status', 'Reglement')
        ->orderBy('date', 'desc')
        ->paginate(8);

        $montantTotalAchat = FournisseurInfo::where('fournisseur_id', $fournisseur)
        ->where('status', 'Achat')
        ->sum('montant');

        $montantTotalReglement = FournisseurInfo::where('fournisseur_id', $fournisseur)
        ->where('status', 'Reglement')
        ->sum('montant');

    
        

        return view('Fournisseurs.detail', compact('fournisseur','fournisseurInfo','achats','reglements','montantTotalReglement','montantTotalAchat'));
    }

    public function storeAchat(Request $request)
    {
        $jsonString=$request->fournisseur;
        //dd($request->fournisseur);

        // Décoder la chaîne JSON en tableau associatif
        $data = json_decode($jsonString, true);

        // Accéder à la valeur de fournisseur_id
        // $fournisseurId = $data['fournisseur_id'];
        $fournisseurId = $data;

        //dd($fournisseurId);

        $fournisseur= new fournisseurInfo();
        $fournisseur->montant = $request->montant;
        $fournisseur->date = $request->date;
        $fournisseur->status = $request->status;
        $fournisseur->fournisseur_id = $fournisseurId;

        $fournisseur->save();
        

        return back()->with('success_message', 'Achat a été enregistrée avec succès');
    }

    public function storeReglement(Request $request)
    {

        $jsonString=$request->fournisseur;
        //dd($request->fournisseur);

        // Décoder la chaîne JSON en tableau associatif
        $data = json_decode($jsonString, true);

        // Accéder à la valeur de fournisseur_id
        $fournisseurId = $data['fournisseur_id'];

        //dd($request);
        $fournisseur= new fournisseurInfo();
        $fournisseur->montant = $request->montant;
        $fournisseur->date = $request->date;
        $fournisseur->status = $request->status;
        $fournisseur->fournisseur_id = $fournisseurId;
        $fournisseur->save();
        
        return back()->with('success_message', 'Reglement a été enregistrée avec succès');
    }


    
    public function create()
    {
        return view('Fournisseurs.create');
    }

    public function store(Fournisseur $fournisseur, saveFournisseurRequest $request)
    {
        //dd(1);
        //Enregistrer un nouveau client
        try {
            $fournisseur->nom = $request->nom;
            $fournisseur->raisonSociale = $request->societe;
            $fournisseur->telephone = $request->telephone;
            $fournisseur->ville = $request->ville;

            $fournisseur->save();

            // dd($client);

            return redirect()->route('fournisseur.index')->with('success_message', 'Fournisseur enregistré avec succès');
        } catch (Exception $e) {
            dd($e);
        }
    }


    public function edit(Fournisseur $fournisseur)
    {
        return view('Fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Fournisseur $fournisseur, saveFournisseurRequest $request)
    {
        //Enregistrer un nouveau département
        try {
            $fournisseur->nom = $request->nom;
            $fournisseur->raisonSociale = $request->societe;
            $fournisseur->telephone = $request->telephone;
            $fournisseur->ville = $request->ville;

            $fournisseur->update();

            return redirect()->route('fournisseur.index')->with('success_message', 'Fournisseur mis à jour avec succès');
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function delete(Fournisseur $fournisseur)
    {
        //Enregistrer un nouveau département
        try {
            $fournisseur->delete();

            return redirect()->route('fournisseur.index')->with('success_message', 'Fournisseur supprimé avec succès');
        } catch (Exception $e) {
            dd($e);
        }
    }



}
