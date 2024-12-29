<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProduitType;

class Produit extends Model
{
    use HasFactory;
     protected $guarded = [''];
    protected $fillable = ['code','dateReception', 'dateExpiration','categorie_id','fournisseur_id','emplacement_id',
                                'libelle','prix','produitType_id','quantite'];


    public function categorie()
    {
        return $this->belongsTo(Categories::class, 'categorie_id');
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    public function emplacement()
    {
        return $this->belongsTo(Emplacement::class, 'emplacement_id');
    }

    public function produitType()
    {
        return $this->belongsTo(produitType::class, 'produitType_id');
    }
}

