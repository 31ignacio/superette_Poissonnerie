@extends('layouts.master2')

@section('content')

    <section class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-4"></div>

                <div class="col-md-4">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title">Informations du Produit</h5>

                        </div>

                        <div class="card-body">

                            <form action="{{route('stock.final')}}" method="post">

                                @csrf

                                <div class="mb-3">

                                    <input type="hidden" id="produit_id" name="produit_id" value="{{ $produit_id }}"

                                        class="form-control" readonly>

                                </div>

                                <div class="mb-3">

                                    <label for="produit_libelle" class="form-label">Produit :</label>

                                    <input type="text" id="produit_libelle" name="produit_libelle"

                                        value="{{ $produit_libelle }}" class="form-control" readonly>

                                </div>

                                <div class="mb-3">

                                    <label for="produit_quantite" class="form-label">Quantité actuelle :</label>

                                    <input type="text" id="produit_quantite" name="produit_quantite"

                                        value="{{ $produit_quantite }}" class="form-control" readonly>

                                </div>

                                <!-- Ajoutez d'autres champs du formulaire au besoin -->

                                <div class="mb-3">

                                    <label for="transferer" class="form-label">Quantité à transférer :</label>

                                    <input type="integer" id="transferer" name="transferer"

                                        class="form-control" required="required">

                                </div>

                                <button type="submit" class="btn btn-primary" onclick="this.style.display='none'">Soumettre</button>

                            </form>

                        </div>

                    </div>

                </div>

                <div class="col-md-4"></div>

            </div>
        </div>

    </section>

@endsection

