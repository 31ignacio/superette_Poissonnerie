<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategorieController extends Controller
{
    
    /**
     * Liste des categorie
     */
    public function index()
    {
        $categories = Categories::paginate(10);

        return view('Categories.index',compact('categories'));
    }


    /**
     * Enregistrer une categorie
     */
    public function store(Categories $categorie, Request $request)
    {
        try {
            $categorie->categorie = $request->categorie;
        
            $categorie->save();

            return back()->with('success_message', 'Client enregistré avec succès');

        } catch (Exception $e) {
           
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());
        }
    }

    /**
     * Supprimer categorie
     */
    public function delete(Categories $categorie)
    {
        try {
            $categorie->delete();
            return back()->with('success_message', 'Catégorie supprimé avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());

        }
    }

    
    /**
     * Editer une categorie
     */
    public function update(Categories $categorie, Request $request)
    {
        try {
            $categorie->categorie = $request->categorie;

            $categorie->update();

            return back()->with('success_message', 'Catégorie mis à jour avec succès');
        } catch (Exception $e) {
            return back()->with('error_message', "Une erreur est survenue : " . $e->getMessage());

        }
    }

}
