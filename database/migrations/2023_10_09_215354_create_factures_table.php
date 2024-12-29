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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->float('quantite');
            $table->dateTime('date');
            $table->integer('prix');
            $table->integer('total');
            $table->text('code');
            $table->integer('totalHT');
            $table->integer('totalTVA');
            $table->integer('totalTTC');
            $table->text('produit');
            $table->integer('montantPaye')->nullable();
            $table->integer('reduction')->nullable();
            $table->integer('montantRendu')->nullable();
            $table->integer('montantFinal')->nullable();
            $table->text('client')->nullable();
            $table->text('client_nom')->nullable();
            $table->unsignedBigInteger('produitType_id');
            $table->foreign('produitType_id')->references('id')->on('produit_types')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
