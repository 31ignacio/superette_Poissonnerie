<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Produit;
use App\Models\grosProduit;
use App\Models\Stock;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class StockController extends Controller
{
    //
    /**
     * Affiche le menu du stock detail
     */
    public function index()
    {

        return view('Stocks.index');
    }

    /**
     * Affiche le menu du stock gros
     */
    public function index2()
    {
        return view('Stocks.index2');
    }

    /**
     * Affiche la page des entres de stock details
     */
    public function entrer()
    {

        $stocks = Stock::where('produitType_id', 1)
        ->orderBy('date', 'desc')
        ->get();

        $produits = grosProduit::where('produitType_id', 1)->get();

        // Paginer les resultats obtenus
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $stocks->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $stocks = new LengthAwarePaginator(
            $currentPageItems,
            $stocks->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('Stocks.entrer', compact('stocks','produits'));
    }

    /**
     * Affiche la page des entres de stock poissonnerie
     */
    public function entrerPoissonnerie()
    {

        $stocks = Stock::where('produitType_id', 3)
        ->orderBy('date', 'desc')
        ->get();

        $produits = grosProduit::where('produitType_id', 3)->get();

        // Paginer les resultats obtenus
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $stocks->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $stocks = new LengthAwarePaginator(
            $currentPageItems,
            $stocks->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('Stocks.entrerPoissonnerie', compact('stocks','produits'));
    }
    
    /**
     * Recherche sur la liste des entrés de stock
    */
    public function rechercheDetail(Request $request){
        
        
        // Initialiser les dates de début et de fin
        $dateDebut = $request->dateDebut ?? now()->startOfDay();
        $dateFin = $request->dateFin ?? now()->endOfDay();

        if($dateDebut > $dateFin){
            return back()->with('error_message','La date début ne peut pas être superieur à la date fin');
        }
        
        // Initialiser la requête pour les stocks avec le produitType_id de 1
        $query = Stock::where('produitType_id', 1);
    
        // Appliquer les filtres de date si fournis
        if ($request->filled('dateDebut')) {
            $query->where('date', '>=', $request->dateDebut);
        }
        
    
        if ($request->filled('dateFin')) {
            $query->where('date', '<=', $request->dateFin);
        }
        // Exécuter la requête en triant par date en ordre décroissant
        $stocks = $query->orderBy('date', 'asc')->get();

        //date du jour
        $date = Carbon::now();

        return view('Stocks.rechercheDetail', compact('stocks','dateDebut','dateFin','date'));
    }


    /**
     * Recherche sur la liste des entrés de la poissonnerie
    */
    public function recherchePoissonnerie(Request $request){
        
        // Initialiser les dates de début et de fin
        $dateDebut = $request->dateDebut ?? now()->startOfDay();
        $dateFin = $request->dateFin ?? now()->endOfDay();

        if($dateDebut > $dateFin){
            return back()->with('error_message','La date début ne peut pas être superieur à la date fin');
        }
        
        // Initialiser la requête pour les stocks avec le produitType_id de 1
        $query = Stock::where('produitType_id', 3);
    
        // Appliquer les filtres de date si fournis
        if ($request->filled('dateDebut')) {
            $query->where('date', '>=', $request->dateDebut);
        }
        
    
        if ($request->filled('dateFin')) {
            $query->where('date', '<=', $request->dateFin);
        }
        // Exécuter la requête en triant par date en ordre décroissant
        $stocks = $query->orderBy('date', 'asc')->get();

        //date du jour
        $date = Carbon::now();

        return view('Stocks.recherchePoissonnerie', compact('stocks','dateDebut','dateFin','date'));
    }
    
    /**
     * Affiche la page des entrés gros
     */
    public function entrerGros()
    {
       
        $stocks = Stock::where('produitType_id', 2)
        ->orderBy('date', 'desc')
        ->get();

        $produits = grosProduit::where('produitType_id', 2)->get();

        // Paginer les resultats obtenus
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $stocks->slice(($currentPage - 1) * $perPage, $perPage)->all();

        // Créer une instance de LengthAwarePaginator
        $stocks = new LengthAwarePaginator(
            $currentPageItems,
            $stocks->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('Stocks.entrerGros', compact('stocks','produits'));
    }
    
    /**
     * Recherche pour entrés gros
     */
    public function rechercheGros(Request $request){
        
        
        // Initialiser les dates de début et de fin
        $dateDebut = $request->dateDebut ?? now()->startOfDay();
        $dateFin = $request->dateFin ?? now()->endOfDay();

        if($dateDebut > $dateFin){
            return back()->with('error_message','La date début ne peut pas être superieur à la date fin');
        }
        
        // Initialiser la requête pour les stocks avec le produitType_id de 1
        $query = Stock::where('produitType_id', 2);
    
        // Appliquer les filtres de date si fournis
        if ($request->filled('dateDebut')) {
            $query->where('date', '>=', $request->dateDebut);
        }
    
        if ($request->filled('dateFin')) {
            $query->where('date', '<=', $request->dateFin);
        }
    
        // Exécuter la requête en triant par date en ordre décroissant
        $stocks = $query->orderBy('date', 'asc')->get();
        //date du jour
        $date = Carbon::now();

        // Retourner les données à la vue
        return view('Stocks.rechercheGros', compact('stocks','dateDebut','dateFin','date'));
    }


    public function sortie()
    {
       
        $factures = Facture::select('date', 'produit', DB::raw('SUM(quantite) as total_quantite'))
        ->where('produitType_id', 1)
        ->groupBy('date', 'produit')
        ->orderBy('date', 'asc')
        ->get();
       
        return view('Stocks.sortie', compact('factures'));
    }


    public function sortieGros()
    {

        $factures = Facture::select('date', 'produit', DB::raw('SUM(quantite) as total_quantite'))
        ->where('produitType_id', 2)
        ->groupBy('date', 'produit')
        ->orderBy('date', 'asc')
        ->get();


        return view('Stocks.sortieGros', compact('factures'));
    }


    /**
     * Actuel stock detail superette
     */
    public function actuel()
    {
        // Récupérer tous les produits avec leurs quantités et les sorties correspondantes
        $produits = grosProduit::leftJoin('factures', 'gros_produits.libelle', '=', 'factures.produit')
            ->select(
                'gros_produits.*',
                DB::raw('COALESCE(SUM(factures.quantite), 0) as total_sortie')
            )
            ->groupBy('gros_produits.id') // Assurez-vous que la clé primaire `id` existe dans gros_produits
            ->get();

        // Calcul du stock actuel pour chaque produit
        foreach ($produits as $produit) {
            $produit->stock_actuel = $produit->quantite - $produit->total_sortie;
        }

        return view('Stocks.actuel', compact('produits'));
    }

    /**
     * Actuel stock de la poissonerie
     */
    public function actuelPoissonerie()
    {
        // Récupérer tous les produits avec leurs quantités et les sorties correspondantes
        $produits = grosProduit::leftJoin('factures', 'gros_produits.libelle', '=', 'factures.produit')
            ->select(
                'gros_produits.*',
                DB::raw('COALESCE(SUM(factures.quantite), 0) as total_sortie')
            )
            ->groupBy('gros_produits.id') // Assurez-vous que la clé primaire `id` existe dans gros_produits
            ->get();

        // Calcul du stock actuel pour chaque produit
        foreach ($produits as $produit) {
            $produit->stock_actuel = $produit->quantite - $produit->total_sortie;
        }

        return view('Stocks.actuelPoissonerie', compact('produits'));
    }


    /**
     * inventair detail superette
     */
    public function indexinventaire()
    {
        $today = Carbon::now();

        //Affiche tout les gros de la table grosProduit
        $produits = grosProduit::where('produitType_id', '=', 1)->get();

        // Remplacer par ceci
        $quantiteSortieParProduit = Facture::select('produit', DB::raw('SUM(quantite) as total_quantite'))
        ->groupBy('produit')
        ->get();

        // Creez un tableau associatif pour stocker la quantite de sortie par produit
        $quantiteSortieParProduitArray = [];
        foreach ($quantiteSortieParProduit as $sortie) {
            $quantiteSortieParProduitArray[$sortie->produit] = $sortie->total_quantite;
        }

        // Calculez le stock actuel pour chaque produit
        foreach ($produits as $produit) {
            if (isset($quantiteSortieParProduitArray[$produit->libelle])) {
                $stockActuel = $produit->quantite - $quantiteSortieParProduitArray[$produit->libelle];
                $produit->stock_actuel = $stockActuel;
            } else {
                // Si la quantite de sortie n'est pas definie, le stock actuel est egal a la quantite totale
                $produit->stock_actuel = $produit->quantite;
            }
        }
        return view('Inventaires.index', compact('produits','today'));
    }

     /**
     * inventair gros superette
     */
    public function indexinventaireGros()
    {
        $today = Carbon::now();

        //Affiche tout les gros de la table grosProduit
        $produits = Produit::where('produitType_id', '=', 2)->get();

        // Remplacer par ceci
        $quantiteSortieParProduit = Facture::select('produit', DB::raw('SUM(quantite) as total_quantite'))
        ->groupBy('produit')
        ->get();

        // Creez un tableau associatif pour stocker la quantite de sortie par produit
        $quantiteSortieParProduitArray = [];
        foreach ($quantiteSortieParProduit as $sortie) {
            $quantiteSortieParProduitArray[$sortie->produit] = $sortie->total_quantite;
        }

        // Calculez le stock actuel pour chaque produit
        foreach ($produits as $produit) {
            if (isset($quantiteSortieParProduitArray[$produit->libelle])) {
                $stockActuel = $produit->quantite - $quantiteSortieParProduitArray[$produit->libelle];
                $produit->stock_actuel = $stockActuel;
            } else {
                // Si la quantite de sortie n'est pas definie, le stock actuel est egal a la quantite totale
                $produit->stock_actuel = $produit->quantite;
            }
        }
        return view('Inventaires.indexGros', compact('produits','today'));
    }


    /**
     * inventair poissonnerie
     */
    public function indexinventairePoissonnerie()
    {
        $today = Carbon::now();

        //Affiche tout les gros de la table grosProduit
        $produits = grosProduit::where('produitType_id', '=', 3)->get();

        // Remplacer par ceci
        $quantiteSortieParProduit = Facture::select('produit', DB::raw('SUM(quantite) as total_quantite'))
        ->groupBy('produit')
        ->get();

        // Creez un tableau associatif pour stocker la quantite de sortie par produit
        $quantiteSortieParProduitArray = [];
        foreach ($quantiteSortieParProduit as $sortie) {
            $quantiteSortieParProduitArray[$sortie->produit] = $sortie->total_quantite;
        }

        // Calculez le stock actuel pour chaque produit
        foreach ($produits as $produit) {
            if (isset($quantiteSortieParProduitArray[$produit->libelle])) {
                $stockActuel = $produit->quantite - $quantiteSortieParProduitArray[$produit->libelle];
                $produit->stock_actuel = $stockActuel;
            } else {
                // Si la quantite de sortie n'est pas definie, le stock actuel est egal a la quantite totale
                $produit->stock_actuel = $produit->quantite;
            }
        }
        return view('Inventaires.indexPoissonnerie', compact('produits','today'));
    }




    public function actuelGros()
    {
        
        $produits =Produit::where('produitType_id', 2)->get();

        $produitType_id = 2; // Remplacez cette valeur par celle que vous souhaitez

        $quantiteSortieParProduit = Facture::select('produit', 'produitType_id', DB::raw('SUM(quantite) as total_quantite'))
        ->where('produitType_id', $produitType_id)
        ->groupBy('produit', 'produitType_id')
        ->get();
        



        // Créez un tableau associatif pour stocker la quantité de sortie par produit
        $quantiteSortieParProduitArray = [];
        foreach ($quantiteSortieParProduit as $sortie) {
            $quantiteSortieParProduitArray[$sortie->produit] = $sortie->total_quantite;
        }

        // Calculez le stock actuel pour chaque produit
        //27/02/2024 j'ai ramener la quantite au niveau de $produit->stock_actuel (il suffit de faire une compaaison avec 'actuel' en haut pour comprendre)
        foreach ($produits as $produit) {
            if (isset($quantiteSortieParProduitArray[$produit->libelle])) {
                $stockActuel = $produit->quantite - $quantiteSortieParProduitArray[$produit->libelle];
                $produit->stock_actuel = $produit->quantite;$produit->produitType_id=2;
            } else {
                // Si la quantité de sortie n'est pas définie, le stock actuel est égal à la quantité totale
                $produit->stock_actuel = $produit->quantite;
            }
        }
        //dd($produits);

        return view('Stocks.actuelGros', compact('produits'));
    }

    /**
     * Enregistrer une entrées de stock detail
     */
    public function store(Request $request)
    {
     
        $stock = new Stock();

        // Obtenir la date du jour
        $dateDuJour = Carbon::now();

        // Récupérer les données JSON envoyées depuis le formulaire
        $stock->libelle = $request->produit;
        
        $stock->quantite = $request->quantite;
        $stock->date = $dateDuJour;
        $stock->produitType_id = 1;

        $stock->save();

        $produit = grosProduit::where('libelle', $request->produit)
        ->where('produitType_id', 1)
        ->first();
                

        // Mettez à jour la quantité du produit
        $nouvelleQuantite = $produit->quantite + $request->quantite;
        $produit->update(['quantite' => $nouvelleQuantite]);

        return redirect()->route('stock.entrer')->with('success_message', 'Stock entrés avec succès.');
    }


    /**
     * Enregistrer une entrées de stock poissonnerie
     */
    public function storePoissonnerie(Request $request)
    {
     
        $stock = new Stock();

        // Obtenir la date du jour
        $dateDuJour = Carbon::now();

        // Récupérer les données JSON envoyées depuis le formulaire
        $stock->libelle = $request->produit;
        
        $stock->quantite = $request->quantite;
        $stock->date = $dateDuJour;
        $stock->produitType_id = 3;

        $stock->save();

        $produit = grosProduit::where('libelle', $request->produit)
        ->where('produitType_id', 3)
        ->first();
                

        // Mettez à jour la quantité du produit
        $nouvelleQuantite = $produit->quantite + $request->quantite;
        $produit->update(['quantite' => $nouvelleQuantite]);

        return redirect()->route('stock.entrerPoissonerie')->with('success_message', 'Stock entrés avec succès.');
    }



    public function storeGros(Request $request)
    {
        //dd($request);
        $stock = new Stock();

        // Obtenir la date du jour
        $dateDuJour = Carbon::now();

        // Vous pouvez formater la date selon vos besoins
        $dateFormatee = $dateDuJour->format('Y-m-d H:i:s');
        // Récupérer les données JSON envoyées depuis le formulaire
        $stock->libelle = $request->produit;
        //$stock->ref = 001;

        $stock->quantite = $request->quantite;
        $stock->date = $dateDuJour;
        $stock->produitType_id = 2;
        //dd($stock);
        $stock->save();

        // $produit = Produit::where('libelle', $request->produit)->first();

        $produit = Produit::where('libelle', $request->produit)
                  ->where('produitType_id', 2)
                  ->first();
            //dd($produit);

        //dd($produit);
        // Mettez à jour la quantité du produit
        $nouvelleQuantite = $produit->quantite + $request->quantite;
        $produit->update(['quantite' => $nouvelleQuantite]);

        return redirect()->route('stock.entrerGros')->with('success_message', 'Stock entrés avec succès.');
    }

    /**
     * L page qui affiche le produit a transferer
     */
    public function transferer(Request $request)
    {
        $produit_id = $request->input('produit_id');
        $produit_libelle = $request->input('produit_libelle');
        $produit_quantite = $request->input('produit_quantite');

        return view('Stocks.transferer', compact('produit_id', 'produit_libelle', 'produit_quantite'));
    }


    public function final(Request $request)
    {
        $id = $request->input('produit_id');
        $libelle = $request->input('produit_libelle');
        $quantite = $request->input('produit_quantite');
        $transferer = $request->input('transferer');

        //control sur la quantité
        if ($quantite < $transferer) {
            return back()->with('success_message', "La quantité actuelle du produit est inférieure à la quantité que vous souhaitez transférer.");
        } else {

            $produits = grosProduit::all()->filter(function ($produit) {
                return $produit->produitType_id == 2;
            });

            //recupere le produit en detail qui a ce libelle
            $produitss = grosProduit::all()->filter(function ($produit) use ($libelle) {
                return $produit->produitType_id == 2 && $produit->libelle == $libelle;
            });

            if ($produitss->isEmpty()) {
                return back()->with('success_message', "Le produit que vous tentez de transférer n'existe pas dans les détails des produits.");
            } else {

                //recupere le produit en gros qui a ce libelle
                $produits1 = Produit::all()->filter(function ($produit) use ($libelle) {
                    return $produit->produitType_id == 2 && $produit->libelle == $libelle;
                });

                //recupere le premier produit
                $produit = $produitss->first(); // Supposons que vous souhaitez travailler avec le premier produit de la collection
                $produit1 = $produits1->first(); // Supposons que vous souhaitez travailler avec le premier produit de la collection

                // Récupérez la quantité du produit et ajoutez la quantité spécifiée dans $transferer
                $quantiteTotale = $produit->quantite + $transferer;
                $quantiteTotale1 = $produit1->quantite - $transferer;

                // Faites ce que vous souhaitez avec $quantiteTotale, par exemple, mettez à jour la quantité dans la base de données
                $produit->update(['quantite' => $quantiteTotale]);
                $produit1->update(['quantite' => $quantiteTotale1]);
               
                // A partir d'ici le code vient de stock actuel (c'est pas propre il faut revoir avec le temps 09/03/24 by ignacio)

                $produits =Produit::where('produitType_id', 2)->get();
        
                $produitType_id = 2; // Remplacez cette valeur par celle que vous souhaitez
        
                $quantiteSortieParProduit = Facture::select('produit', 'produitType_id', DB::raw('SUM(quantite) as total_quantite'))
                ->where('produitType_id', $produitType_id)
                ->groupBy('produit', 'produitType_id')
                ->get();
            
        
                // Créez un tableau associatif pour stocker la quantité de sortie par produit
                $quantiteSortieParProduitArray = [];
                foreach ($quantiteSortieParProduit as $sortie) {
                    $quantiteSortieParProduitArray[$sortie->produit] = $sortie->total_quantite;
                }
        
                // Calculez le stock actuel pour chaque produit
                //27/02/2024 j'ai ramener la quantite au niveau de $produit->stock_actuel (il suffit de faire une compaaison avec 'actuel' en haut pour comprendre)
                foreach ($produits as $produit) {
                    if (isset($quantiteSortieParProduitArray[$produit->libelle])) {
                        $stockActuel = $produit->quantite - $quantiteSortieParProduitArray[$produit->libelle];
                        $produit->stock_actuel = $produit->quantite;$produit->produitType_id=2;
                    } else {
                        // Si la quantité de sortie n'est pas définie, le stock actuel est égal à la quantité totale
                        $produit->stock_actuel = $produit->quantite;
                    }
                }

                return redirect()->route('stock.actuelGros')->with(['produits' => $produits, 'success_message' => 'Produit transféré avec succès']);
        
            }
        }
    }

     public function update(Request $request, $id)
    {

        // Valider les données du formulaire
        $request->validate([
            'libelle' => 'required',
            'quantite' => 'required|numeric',
        ]);
        $stock = Stock::find($id);
        $stock->delete();

        $ancienStock= $stock->quantite;
        
        $stock->libelle = $request->libelle;
        $stock->quantite = $request->quantite;

        $produit = grosProduit::where('libelle', $request->libelle)
        ->where('produitType_id', 1)
        ->first();

        $nouvelleQuantite = ($produit->quantite - $ancienStock );
        $produit->update(['quantite' => $nouvelleQuantite]);
        
        // Mettez à jour la quantité du produit
       
        return redirect()->route('stock.entrer')->with('success_message', 'Stock supprimé avec succès.');

    }
    
    public function transfert()
    {
        
        $produits = grosProduit::all();


        $quantiteSortieParProduit = Facture::select('produit', DB::raw('SUM(quantite) as total_quantite'))
        ->groupBy('produit')
        ->get();


        // Creez un tableau associatif pour stocker la quantite de sortie par produit
        $quantiteSortieParProduitArray = [];
        foreach ($quantiteSortieParProduit as $sortie) {
            $quantiteSortieParProduitArray[$sortie->produit] = $sortie->total_quantite;
        }

        // Calculez le stock actuel pour chaque produit
        foreach ($produits as $produit) {
            if (isset($quantiteSortieParProduitArray[$produit->libelle])) {
                $stockActuel = $produit->quantite - $quantiteSortieParProduitArray[$produit->libelle];
                $produit->stock_actuel = $stockActuel;
            } else {
                // Si la quantite de sortie n'est pas definie, le stock actuel est egal a la quantite totale
                $produit->stock_actuel = $produit->quantite;
            }
        }
        //dd($produits);

        return view('Stocks.transfert', compact('produits'));
    }
    
    
}
