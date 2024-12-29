<?php

namespace App\Http\Controllers;
use App\Models\Transfert;

use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EtatController extends Controller
{
    //
    public function search(Request $request)
    {
        $query = Facture::query();
    
        if ($request->filled('dateDebut')) {
            $query->where('date', '>=', $request->dateDebut);
        }
    
        if ($request->filled('dateFin')) {
            $query->where('date', '<=', $request->dateFin);
        }
    
        if ($request->filled('client')) {
            $query->where('client', 'like', '%' . $request->nom . '%');
        }
        
    
        $results = $query->groupBy('code')->get();
        //dd($results);
    
        return view('Etats/index', compact('results'));
    }
    
    public function search1(Request $request)
    {
        $query = Transfert::query();
        
        // Vérifier si les dates de début et de fin sont fournies et valides
        if ($request->filled('dateDebut') && $request->filled('dateFin')) {
            $dateDebut = \Carbon\Carbon::parse($request->dateDebut)->startOfDay();
            $dateFin = \Carbon\Carbon::parse($request->dateFin)->endOfDay();
    
            // S'assurer que la date de début n'est pas supérieure à la date de fin
            if ($dateDebut > $dateFin) {
                return back()->with('error_message', 'La date début ne peut pas être supérieure à la date fin');
            }
    
            $query->whereBetween('date', [$dateDebut, $dateFin]);
        } else {
            // Appliquer les filtres de date individuellement s'ils sont fournis
            if ($request->filled('dateDebut')) {
                $dateDebut = \Carbon\Carbon::parse($request->dateDebut)->startOfDay();
                $query->where('date', '>=', $dateDebut);
            }
            
            if ($request->filled('dateFin')) {
                $dateFin = \Carbon\Carbon::parse($request->dateFin)->endOfDay();
                $query->where('date', '<=', $dateFin);
            }
        }
            $date= Carbon::now();

        $transferts = $query->get();
        // dd($results);
    
        return view('Stocks/indexTrans', compact('transferts','date'));
    }

    
    

}
