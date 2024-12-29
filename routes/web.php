<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EtatController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\ModeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\EmplacementController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\FournisseurInfoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'handleLogin'])->name('handleLogin');

Route::prefix('admin')->group(function () {
    Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::get('/index', [AdminController::class, 'index'])->name('admin');
    Route::post('/create', [AdminController::class, 'store'])->name('admin.store');
    Route::put('/update/{admin}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('delete/{admin}', [AdminController::class, 'delete'])->name('admin.delete');
    Route::PATCH('/toggleStatus/{admin}', [AdminController::class, 'toggleStatus'])->name('admin.toggleStatus');

    
});

 Route::middleware(['auth'])->group(function(){

    Route::get('/accueil', [AccueilController::class, 'index'])->name('accueil.index');

    Route::prefix('client')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('client.index');
        Route::get('/detail{client}}', [ClientController::class, 'detail'])->name('client.detail');
        Route::post('/create', [ClientController::class, 'store'])->name('client.store');
        Route::put('/update/{client}', [ClientController::class, 'update'])->name('client.update');    
        Route::delete('Client/{client}', [ClientController::class, 'delete'])->name('client.delete');
    });
    

    Route::prefix('fournisseur')->group(function () {
        Route::get('/', [FournisseurController::class, 'index'])->name('fournisseur.index');
        Route::get('/create', [FournisseurController::class, 'create'])->name('fournisseur.create');
        Route::post('/create', [FournisseurController::class, 'store'])->name('fournisseur.store');
        Route::get('/edit/{fournisseur}', [FournisseurController::class, 'edit'])->name('fournisseur.edit');
        Route::put('/update/{fournisseur}', [FournisseurController::class, 'update'])->name('fournisseur.update');    
        Route::get('/fournisseur/{fournisseur}', [FournisseurController::class, 'delete'])->name('fournisseur.delete');
        Route::get('/detail{fournisseur}}', [FournisseurController::class, 'detail'])->name('fournisseur.detail');
        Route::post('/create/achat/', [FournisseurController::class, 'storeAchat'])->name('fournisseur.storeAchat');
        Route::post('/create/reglement/', [FournisseurController::class, 'storeReglement'])->name('fournisseur.storeReglement');


    });
    
    Route::prefix('etat')->group(function () {
        Route::get('/', [EtatController::class, 'search'])->name('etat.index');
        Route::get('/trans', [EtatController::class, 'search1'])->name('transfert.index');
    });
    
    
    Route::prefix('produit')->group(function () {
        Route::get('/', [ProduitController::class, 'index'])->name('produit.index');
        Route::get('/produitsGros', [ProduitController::class, 'index2'])->name('produit.index2');
        Route::post('/create', [ProduitController::class, 'store'])->name('produit.store');
        Route::post('/update/{produit}', [ProduitController::class, 'update'])->name('produit.update');
        
        Route::delete('/{produit}', [ProduitController::class, 'delete'])->name('produit.delete');
    });

    Route::prefix('categorie')->group(function () {
        Route::get('/', [CategorieController::class, 'index'])->name('categorie.index');
        Route::post('/create', [CategorieController::class, 'store'])->name('categorie.store');
        Route::put('/update/{categorie}', [CategorieController::class, 'update'])->name('categorie.update');
        Route::delete('/{categorie}', [CategorieController::class, 'delete'])->name('categorie.delete');
    });
    
    Route::prefix('emplacement')->group(function () {
        Route::get('/', [EmplacementController::class, 'index'])->name('emplacement.index');
        Route::get('/create', [EmplacementController::class, 'create'])->name('emplacement.create');
        Route::post('/create', [EmplacementController::class, 'store'])->name('emplacement.store');
        Route::get('/edit/{emplacement}', [EmplacementController::class, 'edit'])->name('emplacement.edit');
        Route::put('/update/{emplacement}', [EmplacementController::class, 'update'])->name('emplacement.update');
        Route::get('/{emplacement}', [EmplacementController::class, 'delete'])->name('emplacement.delete');
    });

    
    Route::prefix('facture')->group(function () {
        Route::get('/', [FactureController::class, 'index'])->name('facture.index');
        Route::get('/create', [FactureController::class, 'create'])->name('facture.create');
        Route::post('/create', [FactureController::class, 'store'])->name('facture.store');
        Route::get('/edit/{facture}', [FactureController::class, 'edit'])->name('facture.edit');
        Route::put('/update/{facture}', [FactureController::class, 'update'])->name('facture.update');
        Route::get('/details/{code}/{date}',[FactureController::class, 'details'])->name('facture.details');
        Route::get('/annuler',[FactureController::class, 'annuler'])->name('facture.annuler');
        Route::get('/pointJournée', [FactureController::class, 'point'])->name('facture.point');

        Route::get('/pdf/{facture}', [FactureController::class, "pdf"])->name('facture.pdf');
    
        Route::get('/{facture}', [FactureController::class, 'delete'])->name('facture.delete');
        Route::get('/recherche/search', [FactureController::class, 'recherche'])->name('facture.search');
        Route::get('/facture/impression/{code}/{date}', [FactureController::class, 'impression'])->name('facture.impression');

    });
    
    
    Route::prefix('stock')->group(function () {
        Route::get('/index', [StockController::class, 'index'])->name('stock.index');
        Route::get('/index2', [StockController::class, 'index2'])->name('stock.index2');

        Route::get('/entrer', [StockController::class, 'entrer'])->name('stock.entrer');
        Route::get('/entrer/poissonnerie', [StockController::class, 'entrerPoissonnerie'])->name('stock.entrerPoissonerie');

        Route::get('/entrerGros', [StockController::class, 'entrerGros'])->name('stock.entrerGros');

        Route::get('/sortie', [StockController::class, 'sortie'])->name('stock.sortie');
        Route::get('/sortieGros', [StockController::class, 'sortieGros'])->name('stock.sortieGros');

        Route::get('stock/actuel', [StockController::class, 'actuel'])->name('stock.actuel');
        Route::get('stock/actuelPoissonerie', [StockController::class, 'actuelPoissonerie'])->name('stock.actuelPoissonerie');

        Route::get('/actuelGros', [StockController::class, 'actuelGros'])->name('stock.actuelGros');
        Route::get('/transfert', [StockController::class, 'transfert'])->name('stock.transfert');

        Route::get('/transferer', [StockController::class, 'transferer'])->name('stock.tranferer');

         Route::post('/create', [StockController::class, 'store'])->name('stock.store');
         Route::post('/create/Poissonnerie', [StockController::class, 'storePoissonnerie'])->name('stock.storePoissonnerie');

         Route::post('/createGros', [StockController::class, 'storeGros'])->name('stock.storeGros');

         Route::post('stock/final', [StockController::class, 'final'])->name('stock.final');

        Route::get('/inventaires/details', [StockController::class, 'indexinventaire'])->name('inventaires.index');
        Route::get('/inventaires/gros', [StockController::class, 'indexinventaireGros'])->name('inventaires.indexGros');
        Route::get('/inventaires/poissonnerie', [StockController::class, 'indexinventairePoissonnerie'])->name('inventaires.indexPoissonnerie');

        
        Route::get('/recherche/detail', [StockController::class, 'rechercheDetail'])->name('stock.rechercheDetail');
        Route::get('/recherche/poissonnerie', [StockController::class, 'recherchePoissonnerie'])->name('stock.recherchePoissonnerie');
        Route::get('/recherche/gros', [StockController::class, 'rechercheGros'])->name('stock.rechercheGros');
        Route::post('/stock/{id}', [StockController::class, 'update'])->name('stock.update');


    });
    
    

 });
