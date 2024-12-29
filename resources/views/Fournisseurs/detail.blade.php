@extends('layouts.master2')

@section('content')
    <br>
    {{-- Mes bouton en haut (qui affiche le modal) --}}
    <div class="row">
        <div class="col-md-1 col-sm-1"></div>
        <div class="col-md-5 col-sm-5 mb-2">
            <button type="button" class="btn btn-lg btn-success btn-block" data-toggle="modal" data-target="#modal-danger">
                Enregistrer un achat
            </button>
        </div>
        <div class="col-md-5 col-sm-5 mb-2">
            <button type="button" class="btn btn-lg btn-warning btn-block" data-toggle="modal" data-target="#modal-success">
                Enregistrer un règlement
            </button>
        </div>
        <div class="col-md-1 col-sm-1"></div>
    </div>

    <br>
        {{-- Mes messages --}}
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            @if (Session::get('success_message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" style="font-size: 30px;">&times;</span>
                    </button>
                </div>
            @endif
        </div>
        <div class="col-md-2"></div>
    </div>

    <div class="card card-primary card-outline mb-5">
        <div class="card-header">

        </div>
        <div class="card-body">
            {{-- <h4>Left Sided</h4> --}}
            <div class="row">
                <div class="col-md-3 col-sm-2">
                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist"
                        aria-orientation="vertical">
                        <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home"
                            role="tab" aria-controls="vert-tabs-home" aria-selected="true">Achats/Règlements</a>
                        <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile"
                            role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Achats</a>
                        <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill" href="#vert-tabs-messages"
                            role="tab" aria-controls="vert-tabs-messages" aria-selected="false">Règlements</a>
                    </div>
                </div>
                <div class="col-md-9 col-sm-10">
                    <div class="tab-content" id="vert-tabs-tabContent">
                        <div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel"
                            aria-labelledby="vert-tabs-home-tab">


                            <div class="container">

                                <div class="row">
                                    <div class="col-md-9">
                                        <p>Total des achats : <span class="badge bg-success">{{ $montantTotalAchat }}
                                                FCFA</span></p>
                                        <p>Total des reglements : <span
                                                class="badge bg-warning">{{ $montantTotalReglement }} FCFA</span></p>
                                        <p>Créances : <span class="badge bg-info">
                                                {{ $montantTotalAchat - $montantTotalReglement }} FCFA</span></p>
                                    </div>
                                </div>
                                    {{-- Tableau 1 melange de tout --}}
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Status</th>
                                            <!-- Ajoutez d'autres colonnes selon vos besoins -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($fournisseurInfo as $fournisseur)
                                            <tr>
                                                <td class="cell">
                                                    {{ \Carbon\Carbon::parse($fournisseur->date)->format('d/m/Y') }}</td>
                                                <td>{{ $fournisseur->montant }}</td>
                                                <td>
                                                    @if ($fournisseur->status === 'Reglement')
                                                        <span class="badge bg-warning"> {{ $fournisseur->status }}</span>
                                                    @else
                                                        <span class="badge bg-success"> {{ $fournisseur->status }}</span>
                                                    @endif
                                                </td>
                                                <!-- Ajoutez d'autres colonnes selon vos besoins -->
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="cell" colspan="8">
                                                    <div style="text-align: center; padding:3rem"> Aucun enregistrement
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>

                                {{-- LA PAGINATION --}}
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        @if ($fournisseurInfo->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link" aria-hidden="true">&laquo; Précédent</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $fournisseurInfo->previousPageUrl() }}"
                                                    rel="prev" aria-label="Précédent">&laquo; Précédent</a>
                                            </li>
                                        @endif

                                        @if ($fournisseurInfo->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $fournisseurInfo->nextPageUrl() }}"
                                                    rel="next" aria-label="Suivant">Suivant &raquo;</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link" aria-hidden="true">Suivant &raquo;</span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>



                        </div>
                        <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel"
                            aria-labelledby="vert-tabs-profile-tab">

                            <div class="table-responsive">
                                {{-- Tbleau2 (Achat) --}}
                                <table class="table table-bordered">

                                    <thead>
                                        <tr>
                                            <th class="cell">Date</th>
                                            <th class="cell">Montant</th>

                                            <th class="cell">Status</th>

                                            {{-- <th class="cell">Actions</th> --}}

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($achats as $achat)
                                            <tr>
                                                <td class="cell">
                                                    {{ \Carbon\Carbon::parse($achat->date)->format('d/m/Y') }}</td>
                                                <td>{{ $achat->montant }}</td>
                                                <td>
                                                    <span class="badge bg-success"> {{ $achat->status }}</span>

                                                </td>
                                                <!-- Ajoutez d'autres colonnes selon vos besoins -->
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="cell" colspan="8">
                                                    <div style="text-align: center; padding:3rem"> Aucun enregistrement
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforelse


                                    </tbody>
                                </table>

                                {{-- LA PAGINATION --}}
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        @if ($achats->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link" aria-hidden="true">&laquo; Précédent</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $achats->previousPageUrl() }}"
                                                    rel="prev" aria-label="Précédent">&laquo; Précédent</a>
                                            </li>
                                        @endif

                                        @if ($achats->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $achats->nextPageUrl() }}" rel="next"
                                                    aria-label="Suivant">Suivant &raquo;</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link" aria-hidden="true">Suivant &raquo;</span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>

                            </div>


                        </div>
                        <div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel"
                            aria-labelledby="vert-tabs-messages-tab">


                            <div class="table-responsive">
                                {{-- Tableau3 Reglement --}}
                                <table class="table table-bordered">

                                    <thead>
                                        <tr>
                                            <th class="cell">Date</th>
                                            <th class="cell">Montant</th>

                                            <th class="cell">Status</th>

                                            {{-- <th class="cell">Actions</th> --}}

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($reglements as $reglement)
                                            <tr>
                                                <td class="cell">
                                                    {{ \Carbon\Carbon::parse($reglement->date)->format('d/m/Y') }}</td>
                                                <td>{{ $reglement->montant }}</td>
                                                <td>
                                                    <span class="badge bg-warning"> {{ $reglement->status }}</span>

                                                </td>
                                                <!-- Ajoutez d'autres colonnes selon vos besoins -->
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="cell" colspan="8">
                                                    <div style="text-align: center; padding:3rem"> Aucun enregistrement
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>

                                {{-- LA PAGINATION --}}
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        @if ($reglements->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link" aria-hidden="true">&laquo; Précédent</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $reglements->previousPageUrl() }}"
                                                    rel="prev" aria-label="Précédent">&laquo; Précédent</a>
                                            </li>
                                        @endif

                                        @if ($reglements->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $reglements->nextPageUrl() }}"
                                                    rel="next" aria-label="Suivant">Suivant &raquo;</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link" aria-hidden="true">Suivant &raquo;</span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>


                        </div>

                    </div>
                </div>
            </div>

        </div>
        <!-- /.card -->
    </div>


    {{-- Modal enregistrer un achat --}}

    <div class="modal fade" id="modal-danger">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Enregistrer un achat</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('fournisseur.storeAchat') }}">
                        @csrf
                        <div class="form-group">
                            <label for="charge">Montant de l'achat:</label>
                            <input type="number" class="form-control" name="montant" id="montant" required
                                placeholder="Montant" />
                        </div>

                        <div class="form-group">
                            <label for="date">Date :</label>
                            <input type="date" class="form-control" name="date" id="date" required />
                        </div>

                        <input type="hidden" class="form-control" name="status" id="status" value="Achat"
                            required />
                        <input type="hidden" class="form-control" name="fournisseur" id="fournisseur"
                            value="{{ $fournisseur }}" required />

                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>

                    </form>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{-- Modal enregistrer un reglement --}}

    <div class="modal fade" id="modal-success">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Enregistrer une reglement</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('fournisseur.storeReglement') }}">
                        @csrf
                        <div class="form-group">
                            <label for="recette">Montant du reglement :</label>
                            <input type="number" class="form-control" name="montant" id="montant" required
                                placeholder="Montant" />
                        </div>

                        <div class="form-group">
                            <label for="date">Date :</label>
                            <input type="date" class="form-control" name="date" id="date" required />
                        </div>

                        <input type="hidden" class="form-control" name="status" id="status" value="Reglement"
                            required />
                        <input type="hidden" class="form-control" name="fournisseur" id="fournisseur"
                            value="{{ $fournisseur }}" required />

                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
