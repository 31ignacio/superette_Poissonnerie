@extends('layouts.master2')
@section('content')
    <section class="content">

        <div class="container-fluid">

            <div class="row">
                @if($user->role_id==2 || $user->role_id==3 || $user->role_id==5)
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="info-box bg-info">
                            <span class="info-box-icon text-white"><i class="fas fa-calendar-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Point d'hier</span>
                                <?php
                                $sommeMontantHier_format = number_format($sommeMontantHier, 0, ',', '.');
                                ?>
                                <span class="info-box-number clignotant">{{ $sommeMontantHier_format }} FCFA</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="info-box bg-success">
                            <span class="info-box-icon text-white"><i class="fas fa-sun"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Point de la journée</span>
                                <?php
                                $sommeMontant_format = number_format($sommeMontant, 0, ',', '.');
                                ?>
                                <span class="info-box-number">{{ $sommeMontant_format }} FCFA</span>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="info-box bg-info">
                            <span class="info-box-icon text-white"><i class="fas fa-calendar-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Point d'hier Superette</span>
                                <?php
                                $sommeMontantHier_format = number_format($sommeMontantHierS, 0, ',', '.');
                                ?>
                                <span class="info-box-number clignotant">{{ $sommeMontantHier_format }} FCFA</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="info-box bg-success">
                            <span class="info-box-icon text-white"><i class="fas fa-sun"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Point d'hier Poissonnerie</span>
                                <?php
                                $sommeMontant_format = number_format($sommeMontantHierP, 0, ',', '.');
                                ?>
                                <span class="info-box-number clignotant">{{ $sommeMontant_format }} FCFA</span>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header">

                            <h3 class="card-title">Les ventes d'hier</h3>

                        </div>

                        <div class="card-body table-responsive">

                            <table id="example1" class="table table-bordered table-striped">

                                <thead>

                                    <tr>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Total TTC</th>
                                        <th>Montant Perçu</th>
                                        <th>Reliquat</th>
                                        <th>Type</th>
                                        @auth
                                            @if (auth()->user()->role_id == 1)
                                                <th>Caissier</th>
                                            @endif
                                        @endauth
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($facturesHier as $factureUnique)
                                        @if ($role == 1)
                                            <tr>
                                                <td>{{ $factureUnique->client_nom }}</td>

                                                <td>{{ date('d/m/Y', strtotime($factureUnique->date)) }}</td>

                                                <td>{{ $factureUnique->montantFinal }}</td>

                                                <td>{{ $factureUnique->montantPaye }}</td>

                                                <td>
                                                    <span class="right badge badge-info"><b>{{ $factureUnique->montantPaye - $factureUnique->montantFinal }}</b></span>

                                                </td>
                                                <td>
                                                    @if($factureUnique->produitType_id == 3)
                                                        <span class="right badge badge-warning">Poissonnerie</span>
                                                    @else
                                                        <span class="right badge badge-success">Superette</span>
                                                    @endif
                                                </td>

                                                @auth
                                                    @if (auth()->user()->role_id == 1)
                                                        <td><b>{{ $factureUnique->user->name }}</b></td>
                                                    @endif
                                                @endauth
                                                <td>
                                                    <a href="{{ route('facture.details', ['code' => $factureUnique->code, 'date' => $factureUnique->date]) }}"
                                                        class="btn-sm btn-primary">Détail
                                                    </a>
                                                    @auth
                                                        @if (auth()->user()->role_id == 1)
                                                            <a href="#" class="btn-sm btn-danger" data-toggle="modal"
                                                                data-target="#confirmationModal"
                                                                onclick="updateModal('{{ $factureUnique->code }}')">Annuler</a>
                                                        @endif
                                                    @endauth
                                                </td>
                                            </tr>
                                        @elseif($nom == $factureUnique->user->name)
                                            <tr>

                                                <td>{{ $factureUnique->client_nom }}</td>


                                                <td>{{ date('d/m/Y', strtotime($factureUnique->date)) }}</td>

                                                <td>{{ $factureUnique->montantFinal }}</td>

                                                <td>{{ $factureUnique->montantPaye }}</td>

                                                <td><span
                                                        class="right badge badge-info"><b>{{ $factureUnique->montantPaye - $factureUnique->montantFinal }}</b></span>

                                                </td>



                                                @auth

                                                    @if (auth()->user()->role_id == 1)
                                                        <td><b>{{ $factureUnique->user->name }}</b></td>
                                                    @endif

                                                @endauth
                                                <td>

                                                    <a href="{{ route('facture.details', ['code' => $factureUnique->code, 'date' => $factureUnique->date]) }}"
                                                        class="btn-sm btn-primary">Détail</a>



                                                    @auth

                                                        @if (auth()->user()->role_id == 1)
                                                            <a href="#" class="btn-sm btn-danger" data-toggle="modal"
                                                                data-target="#confirmationModal"
                                                                onclick="updateModal('{{ $factureUnique->code }}')">Annuler</a>
                                                        @endif

                                                    @endauth

                                                </td>

                                            </tr>
                                        @else
                                            <span></span>
                                        @endif
                                    @endforeach

                                    </tfoot>

                            </table>



                            <br>

                           

                        </div>

                        <!-- /.card-body -->

                    </div>

                    <!-- /.card -->

                </div>

                <!-- /.col -->

            </div>

            <!-- /.row -->

        </div>

        <!-- /.container-fluid -->



        {{-- Modal pour annuler une facture --}}

        <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
            aria-labelledby="confirmationModalLabel" aria-hidden="true">

            <div class="modal-dialog" role="document">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                            <span aria-hidden="true">&times;</span>

                        </button>

                    </div>

                    <form method="get" action="{{ route('facture.annuler') }}">

                        @csrf

                        <div class="modal-body">

                            Voulez-vous annuler cette facture ?

                        </div>

                        <input type="hidden" id="factureCode" name="factureCode">

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>

                            <button type="submit" class="btn btn-danger">Oui</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function updateModal(code) {
            // Mettez à jour le contenu du span avec le code spécifique

            document.getElementById('factureCode').value = code;

        }
    </script>


    <style>
        @keyframes clignoter {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0;
        }
    }

    .clignotant {
        animation: clignoter 1s infinite;
    }

    </style>
@endsection
