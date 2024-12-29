<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gros_produits', function (Blueprint $table) {
            $table->id();
            $table->text('code');
            $table->string('libelle');
            $table->float('quantite')->nullable();
            $table->dateTime('dateReception');
            $table->text('prix');
            $table->dateTime('dateExpiration');
            $table->unsignedBigInteger('produitType_id');
            $table->foreign('produitType_id')->references('id')->on('produit_types')->onDelete('cascade');
            $table->unsignedBigInteger('emplacement_id');
            $table->foreign('emplacement_id')->references('id')->on('emplacements')->onDelete('cascade');
            $table->unsignedBigInteger('categorie_id');
            $table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('fournisseur_id');
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gros_produits');
    }
};
