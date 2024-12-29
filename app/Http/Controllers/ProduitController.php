<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\ProduitType;
use App\Models\Categories;
use App\Models\Emplacement;
use App\Models\Fournisseur;
use App\Models\grosProduit;
use Exception;

use Illuminate\Http\Request;

class ProduitController extends Controller
{
    //Liste de tout les produits
    public function index()
    {
        $produits = Produit::all();
        $categories = Categories::all();
        $emplacements = Emplacement::all();
        $fournisseurs = Fournisseur::all();
        $produitTypes = ProduitType::all();

        return view('Produits.index',compact('produits','categories','emplacements','fournisseurs','produitTypes'));
    }

    // Liste des produits filtré en gros
    public function index2()
    {
        $produitsGros = Produit::all()->filter(function ($produit) {
            return $produit->produitType_id == 2;
        });

        return view('Produits.index2',compact('produitsGros'));
    }


    //Enregistrer le produit dans la base de donnée
    public function store(Request $request)
    {

        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'categorie' => 'required|exists:categories,id',
            'fournisseur' => 'required|exists:fournisseurs,id',
            'emplacement' => 'required|exists:emplacements,id',
            'prix' => 'required|numeric|min:0',
            'produitType' => 'required|exists:produit_types,id',
            'quantite' => 'required|numeric|min:0', // Accepte les décimaux
            'dateExpiration' => 'nullable|date',
            'dateReception' => 'nullable|date',
        ]);

        // Générer un code unique pour le produit
        $code = substr($request->libelle, 0, 3) . '_' . rand(0, 10000);

        // Vérifier si le produit existe déjà
        $existingProduct = Produit::where('libelle', $request->libelle)
            ->where('categorie_id', $request->categorie)
            ->first();

        if ($existingProduct) {
            return back()->with('error_message', 'Ce produit existe déjà.');
        }

        // Créer un tableau de données communes
        $productData = [
            'code' => $code,
            'categorie_id' => $request->categorie,
            'fournisseur_id' => $request->fournisseur,
            'emplacement_id' => $request->emplacement,
            'libelle' => $request->libelle,
            'prix' => $request->prix,
            'produitType_id' => $request->produitType,
            'quantite' => $request->quantite,
            'dateExpiration' => $request->dateExpiration,
            'dateReception' => $request->dateReception,
        ];

        try {
            // Enregistrer dans la table `produits`
            Produit::create($productData);

            // Vérifier si c'est un produit de type "gros"
            if ($request->produitType == 1 || $request->produitType == 3) {
                grosProduit::create($productData);
            } else {
                // Ajouter une quantité par défaut pour les autres types de produits
                $productData['quantite'] = 0;
                grosProduit::create($productData);
            }

            return redirect()->route('produit.index')
                ->with('success_message', 'Produit enregistré avec succès.');

        } catch (Exception $e) {
            return back()->with('error_message', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    

    /**
     * Modifier un produit
     */
    public function update(Produit $produit, Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'categorie' => 'required|exists:categories,id',
            'fournisseur' => 'required|exists:fournisseurs,id',
            'emplacement' => 'required|exists:emplacements,id',
            'libelle' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'produitType' => 'required|exists:produit_types,id',
            'dateExpiration' => 'required|date',
            'dateReception' => 'required|date',
        ]);


        try {
            // Mise à jour des données communes pour la table `produits`
            $produit->update([
                'categorie_id' => $request->categorie,
                'fournisseur_id' => $request->fournisseur,
                'emplacement_id' => $request->emplacement,
                'libelle' => $request->libelle,
                'prix' => $request->prix,
                'produitType_id' => $request->produitType,
                'dateExpiration' => $request->dateExpiration,
                'dateReception' => $request->dateReception,
            ]);

            // Recherche ou création d'une entrée correspondante dans `grosProduits`
            $grosProduit = grosProduit::firstOrNew(['code' => $produit->code]);

            $grosProduit->fill([
                'categorie_id' => $request->categorie,
                'fournisseur_id' => $request->fournisseur,
                'emplacement_id' => $request->emplacement,
                'libelle' => $request->libelle,
                'prix' => $request->prix,
                'produitType_id' => $request->produitType,
                'dateExpiration' => $request->dateExpiration,
                'dateReception' => $request->dateReception,
            ])->save();


            return back()->with('success_message', 'Produit modifié avec succès.');
        } catch (Exception $e) {
            return back()->with('error_message', 'Une erreur est survenue lors de la modification du produit.');
        }
    }


    public function delete(Produit $produit)
    {
        try {
            // Recherche du GrosProduit ayant le même code que le Produit
            $grosProduit = grosProduit::where('code', $produit->code)->first();

            // Si le GrosProduit existe, on le supprime
            if ($grosProduit) {
                $grosProduit->delete();
            }

            // Suppression du Produit
            $produit->delete();

            return back()->with('success_message', 'Produit supprimé avec succès');
        } catch (Exception $e) {
            
            return back()->with('error_message', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

}
