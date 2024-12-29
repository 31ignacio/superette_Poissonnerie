@extends('layouts.master2')

@section('content')

    <section class="content">
        <div class="container-fluid">
             <a href="{{ route('facture.point') }}" class="btn bg-gradient-success">
                <i class="fas fa-chart-bar"></i> Voir les ventes d'hier
            </a><br><br>  

                <div class="card table-responsive">
                    <div class="card-header">
                        <h3 class="card-title">Liste des factures</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body col-md-12">
                        
                        <form method="GET" action="{{ route('facture.search') }}">
                            @csrf
                            @method('GET')
            
                            <div class="row mb-3">
                                <div class="col-md-4 col-lg-4 col-sm-4">
                                    <label>Date Début :</label>
                                    <input type="date" class="form-control" name="dateDebut">
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-4">
                                    <label>Date Fin :</label>
                                    <input type="date" class="form-control" name="dateFin">
                                </div>
                                
                                    
                                <div class="col-md-4 col-lg-4 col-sm-4 mt-4">
        
                                    <button type="submit" class="btn btn-lg btn-default">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            
                            <h5>Liste des facture du {{ date('d/m/Y', strtotime($dateDebut)) }} au {{ date('d/m/Y', strtotime($dateFin)) }} </h5>
                        </div>
                        
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Total TTC</th>
                                    <th>Montant Perçu</th>
                                    <th>Reliquat</th>
                                    @auth
                                        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 4)

                                            <th>Caissier</th>
                                        @endif
                                    @endauth
                                    <th>Actions</th>

                                    <!-- Autres colonnes -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($codesFacturesUniques as $factureUnique)
                                    @if ($role==1 || $role==4)                                            
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $factureUnique->client_nom }}</td>
                                                    <td>{{ date('d/m/Y', strtotime($factureUnique->date)) }}</td>
                                                    <td>{{ $factureUnique->montantFinal }}</td>
                                                    <td>{{ $factureUnique->montantPaye }}</td>
                                                    <td><span
                                                            class="right badge badge-info"><b>{{ $factureUnique->montantPaye - $factureUnique->montantFinal }}</b></span>
                                                    </td>

                                                    @auth
                                                        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 4)

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
                                        
                                    @elseif($nom == $factureUnique->user->name)

                                                <tr>
                                                    @if ($factureUnique->client == null)
                                                        <td>Client</td>
                                                    @else
                                                        <td>{{ $factureUnique->client }}</td>
                                                    @endif

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
                                                        <div class="d-flex flex-column flex-sm-row"> <!-- Utiliser la classe flexbox -->
                                                            <a href="{{ route('facture.details', ['code' => $factureUnique->code, 'date' => $factureUnique->date]) }}"
                                                                class="btn-sm btn-primary mb-2 mb-sm-0 mr-sm-2">Détail</a> <!-- Ajouter des marges et des classes de grille -->
                                                    
                                                            @auth
                                                                @if (auth()->user()->role_id === 1)
                                                                    <a href="#" class="btn-sm btn-danger" data-toggle="modal" data-target="#confirmationModal"
                                                                        onclick="updateModal('{{ $factureUnique->code }}')">Annuler</a>
                                                                @endif
                                                            @endauth
                                                        </div>
                                                    </td>
                                                    
                                                </tr>
                                    
                                    @else

                                    @endif
                                @endforeach
                        </table>

                    </div>
                        
                </div>
                   
        </div>

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
                //alert(code)
                // Mettez à jour le contenu du span avec le code spécifique
                document.getElementById('factureCode').value = code;
            }
        </script>
    @endsection
