<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Client;
use App\Models\Emplacement;

use App\Models\grosProduit;

use App\Models\ProduitType;
use DateTime; // Importez la classe DateTime en haut de votre fichier
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FactureController extends Controller
{
    //

    public function index()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        $nom = $user->name;
        $role=$user->role_id;

        // Recuperer la somme du montantFinal pour l'utilisateur connecte et le rôle donne
        $sommeMontant = Facture::where('user_id', $user->id)
            ->whereDate('date', now()) // Filtre pour la date du jour
            ->sum('montantFinal');

        $factures = Facture::all();
        $client = Client::all();

        // Creez une collection unique en fonction des colonnes code, date, client et totalHT
        $codesFacturesUniques = $factures
        ->unique(function ($facture) {
            return $facture->code . $facture->date . $facture->client . $facture->totalHT . $facture->emplacement;
        })
        ->sortByDesc('date');

            
        return view('Factures.index', compact('factures', 'codesFacturesUniques','nom','role'));
    }
    /**
     * Imprimer une facture xprint
     */
    public function impression($code, $date)
    {
        // Vous récupérez la facture en fonction du code et de la date
        $factures = Facture::where('date', $date)->where('code', $code)->get();
        
        // Retournez la vue dédiée à l'impression
        return view('Factures.impression', compact('factures', 'date', 'code'));
    }

    /**
     * Rechercher une facture sur la liste des facture
     */
     public function recherche(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        $nom = $user->name;
        $role = $user->role_id;
        
        // Initialiser les dates de début et de fin
        $dateDebut = $request->dateDebut ?? now()->startOfDay();
        $dateFin = $request->dateFin ?? now()->endOfDay();
        
        if($dateDebut > $dateFin){
            return back()->with('error_message','La date début ne peut pas être superieur à la date fin');
        }

        
        // Créer une requête pour les factures filtrées par date
        $query = Facture::query();
        
        if ($request->filled('dateDebut')) {
            $query->where('date', '>=', $dateDebut);
        }
        
        if ($request->filled('dateFin')) {
            $query->where('date', '<=', $dateFin);
        }
        
        // Récupérer les factures filtrées par date
        $factures = $query->get();
        
        // Récupérer la somme du montantFinal pour l'utilisateur connecté et la date filtrée
        $sommeMontant = $factures->where('user_id', $user->id)->sum('montantFinal');
        
        // Créez une collection unique en fonction des colonnes code, date, client et totalHT
        $codesFacturesUniques = $factures
            ->unique(function ($facture) {
                return $facture->code . $facture->date . $facture->client . $facture->totalHT . $facture->emplacement;
            })
            ->sortByDesc('date');
        
        
        return view('Factures.recherche', compact('factures', 'nom', 'role', 'codesFacturesUniques', 'dateDebut', 'dateFin'));
    }

    public function point()
    {
        $user = Auth::user();
        $nom = $user->name;
        $role=$user->role_id;
        $role_id = $user->role_id;
        $user_id = $user->id;
       
        $factures = Facture::where('user_id', $user_id)->get();
       $facture = Facture::all();

       // Si l'utilisateur a le rôle avec role_id=2
        if ($role_id == 2 or $role_id == 3 or $role_id == 5 ) {
           
           $codesFacturesUniques = $factures->unique(function ($facture) {
                return $facture->code . $facture->date . $facture->totalTTC . $facture->montantPaye . $facture->mode;
            })->sortByDesc('date');
            
            $facturesAujour = $codesFacturesUniques->where('date', Carbon::today());
            $facturesHier = $codesFacturesUniques->where('date', Carbon::yesterday());

            $sommeMontant = $facturesAujour->sum('montantFinal');
            $sommeMontantHier = $facturesHier->sum('montantFinal');
            $sommeMontantHierS=0;
            $sommeMontantHierP=0;
            
        } else {
           // Récupérer la somme totale du montantFinal pour la journée
            $codesFacturesUniques = $facture->unique(function ($factur) {
                return $factur->code . $factur->date . $factur->totalTTC . $factur->montantPaye . $factur->mode;
            })->sortByDesc('date');
            // Filtrer les factures par date d'aujourd'hui
            //$facturesAujourdhui = $codesFacturesUniques->where('date',Carbon::today());
            $facturesHier = $codesFacturesUniques->where('date', Carbon::yesterday());
            
            //$sommeMontant = $facturesAujourdhui->sum('montantFinal');
            $sommeMontantHierP = $facturesHier->where('produitType_id',3)->sum('montantFinal');
            $sommeMontantHierS = $facturesHier->where('produitType_id',1)->sum('montantFinal');
            $sommeMontantHier=0;
            $sommeMontant=0;

        }
           
            $factures = Facture::all();

        return view('Factures.point', compact('user','factures', 'facturesHier','nom','role','sommeMontant','sommeMontantHier','sommeMontantHierP','sommeMontantHierS'));
    }

    /**
     * Afficher les details d'une facture
     */
    public function details($code, $date)
    {
        $factures = Facture::all();
        
        return view('Factures.details', compact('date', 'code', 'factures'));
    }

    /**
     * Annuler une facture
     */
    public function annuler(Request $request)
    {
        
       $code=$request->factureCode;
      //dd($code);
        $factures = Facture::select('produit', 'quantite')->where('code', $code)->get();
        //dd($request,$factures);
        foreach ($factures as $facture) {
            //c'est la tu feras le jeu
            $produit = grosProduit::where('libelle', $facture->produit)->first();

            if ($produit) {
                $nouvelleQuantite = $produit->quantite + $facture->quantite - $facture->quantite; // Mettez à jour la nouvelle quantité
        
                // Assurez-vous de mettre à jour le produit avec la nouvelle quantité correcte
                $produit->quantite = $nouvelleQuantite;
                $produit->save();
            }
           // dd($produit);
        }
        //dd($produit);
        // Suppression de toutes les factures avec le code spécifié
        Facture::where('code', $code)->delete();

        //dd($fa);
        return back()->with('success_message', 'La facture a été annulée avec succès.');
    }

    /**
     * Afficher la page pour enregistrer une facture
     */
    public function create()
    {
        $emplacements = Emplacement::all();
        $clients = Client::all();
        $produits = grosProduit::all();
        $user=Auth::user();

        $produitTypes = ProduitType::all();


        $quantiteSortieParProduit = Facture::select('produit', DB::raw('SUM(quantite) as total_quantite'))
            ->groupBy('produit')
            ->get();

        // Créez un tableau associatif pour stocker la quantité de sortie par produit
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
                // Si la quantité de sortie n'est pas définie, le stock actuel est égal à la quantité totale
                $produit->stock_actuel = $produit->quantite;
            }
        }
        return view('Factures.create', compact('clients', 'emplacements','produits','produitTypes','user'));
    }

    /**
     * Enregistrer la facture
     */
    public function store(Request $request)
    { 
       
        if (Auth::check()) {

            // Récupérer les données JSON envoyées depuis le formulaire
            $donnees = json_decode($request->input('donnees'));
            $client_id = $request->client;
            $parts = explode(' ', $client_id);
            $client_id = $parts[0]; // Contient "2"
            $client_nom = $parts[1]; // Contient "zz"
            $dateString = $request->date;
            $totalHT = $request->totalHT;
            $totalTVA = $request->totalTVA;
            $totalTTC = $request->totalTTC;
            $montantPaye = $request->montantPaye;
            $remise = $request->remise;
            $montantFinal = $request->montantFinal;

            $produitType = $request->produitType;
            // Recupere l'utilisateur connecte
            $idUser = Auth::user()->id;
            
            $prefix = 'Fact_';
            $nombreAleatoire = rand(0, 10000); // Utilisation de rand()

            // Formatage du nouveau matricule avec la partie numerique
            $code = $prefix . $nombreAleatoire;

            // Convertissez la date en un objet DateTime
            $date = new DateTime($dateString);
            try {
                // Parcourez chaque element de $donnees et enregistrez-les dans la base de donnees
                foreach ($donnees as $donnee) {
                    // Creez une nouvelle instance du modele Facture pour chaque element
                    $facture = new Facture();

                    // Remplissez les propriétés du modèle avec les données
                    $facture->client = $client_id; 
                    $facture->client_nom = $client_nom; 
                    $facture->date = $date;
                    $facture->produitType_id = $produitType;
                    $facture->totalHT = $totalHT;
                    $facture->totalTVA = $totalTVA;
                    $facture->totalTTC = $totalTTC;
                    $facture->montantPaye =  $montantPaye;
                    $facture->montantRendu =  $montantPaye - $totalTTC;
                    // Vous pouvez accéder aux propriétés de chaque objet JSON
                    $facture->quantite = $donnee->quantite;
                    $facture->produit= $donnee->produit;
                    $facture->prix = $donnee->prix;
                    $facture->total = $donnee->total;
                    $facture->code = $code;
                    $facture->user_id =$idUser;
                    $facture->reduction =$remise;
                    $facture->montantFinal =$montantFinal;
                    $facture->save();
                }
                return new Response(200);
            } catch (Exception $e) {
               
                return new Response(500);
            }
        }else {
            return redirect()->route('login')->with('success_message', 'Veuillez vous connecter pour accéder à cette page.');
        }
        return response()->json(['message' => 'Données enregistrées avec succès']);
    }

    /**
     * Générer pdf des facture (revoir)
     */
    public function pdf($facture,Request $request)
    {
        $date = $request->input('date');
        $code = $request->input('code');
        //$id = $request->input('id');

        try {
            //recuperer tout les information de l'entreprise
            $factures = Facture::all();

            //$name= $facture['date'];
          // Chargez la vue Laravel que vous souhaitez convertir en PDF
        $html = View::make('Factures.facture',compact('factures','date','code'))->render();
            // Créez une instance de Dompdf
        $dompdf = new Dompdf();
        // Chargez le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendez le PDF
        $dompdf->render();

        // Téléchargez le PDF
        return $dompdf->stream('Facture .pdf', ['Attachment' => false]);

        } catch (Exception $e) {
            dd($e);
            throw new Exception("Une erreur est survenue lors du téléchargement de la liste");
        }
    }

}
