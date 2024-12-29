<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facture;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class AccueilController extends Controller
{
    

    public function index(){

        $nombreClient = Client::count();
       
        $factures = Facture::all()->unique('code');
        $sommeTotalTTC = 0;
        $sommeMontantDu=0;
        foreach ($factures as $facture) {
            $sommeTotalTTC += $facture->montantFinal;
            $sommeMontantDu += $facture->montantDu;
        }

        $facture = Facture::all();
  
        $role=Auth::user()->role_id;
        // Récupérer la somme totale du montantFinal pour la journée
        $codesFacturesUniques = $facture->unique(function ($factur) {
            return $factur->code . $factur->date . $factur->totalTTC . $factur->montantPaye . $factur->mode;
        })->sortByDesc('date');
        // Filtrer les factures par date d'aujourd'hui
        $facturesAujourdhuiSuper = $codesFacturesUniques
        ->where('date', Carbon::today())
        ->where('produitType_id', 1);

        $facturesAujourdhuiPoissonnerie = $codesFacturesUniques
        ->where('date', Carbon::today())
        ->where('produitType_id', 3);

        $facturesAujourdhui = $codesFacturesUniques
        ->where('date', Carbon::today());
            
        $sommeMontant = $facturesAujourdhuiSuper->sum('montantFinal');
        $sommeMontantPoissonnerie = $facturesAujourdhuiPoissonnerie->sum('montantFinal');


        return view('Accueil.index',compact('role','nombreClient','sommeTotalTTC','sommeMontant','sommeMontantPoissonnerie','facturesAujourdhui','facturesAujourdhuiSuper','facturesAujourdhuiPoissonnerie'));
    }
}
