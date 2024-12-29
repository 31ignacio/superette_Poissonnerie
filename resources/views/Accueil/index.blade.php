@extends('layouts.master2')

@section('content')
  

    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $nombreClient }}</h3>
                            <p><i class="fas fa-users"></i> Mes clients</p>
                        </div>

                        <a href="{{ route('client.index') }}" class="small-box-footer">Plus d'information <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 style="font-size: 190%;">Facture</h3>

                            <p>Ajouter un facture</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        @auth
                            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2 | auth()->user()->role_id == 5)
                                <a href="{{ route('facture.create') }}" class="small-box-footer">Plus d'information<i
                                class="fas fa-arrow-circle-right"></i></a>       
                            @else
                                <a href="" class="small-box-footer">Plus d'information<i
                                class="fas fa-arrow-circle-right"></i></a>                                 
                            @endif
                        @endauth
                       
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 style="font-size: 190%;">Stocks</h3>

                            <p>Mon stock</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        @auth
                            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 5)
                                <a href="{{ route('stock.index') }}" class="small-box-footer">Plus d'information<i
                                class="fas fa-arrow-circle-right"></i></a>       
                            @else
                            <a href="" class="small-box-footer">Plus d'information<i
                                class="fas fa-arrow-circle-right"></i></a>                                 
                            @endif
                        @endauth
                        
                    </div>
                </div>
                <!-- ./col -->

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            @auth
                                @if (auth()->user()->role_id == 1)
                                    <h3 style="font-size: 170%;">{{ number_format($sommeTotalTTC, 0, ',', '.') }} CFA</h3>
                                @else
                                    <h3>---</h3>
                                @endif
                            @endauth

                            <p>Somme totale des ventes</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        @auth
                            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 5)
                            <a href="{{ route('facture.index') }}" class="small-box-footer">Plus d'information<i
                                class="fas fa-arrow-circle-right"></i></a>       
                            @else
                            <a href="" class="small-box-footer">Plus d'information<i
                                class="fas fa-arrow-circle-right"></i></a>                                 
                            @endif
                        @endauth
                        
                    </div>
                </div>

                <!-- ./col -->
            </div>


            <div class="row">
                
                @if($role != 5)
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1 blink"><i class="fas fa-shopping-cart"></i></span>
            
                            <div class="info-box-content">
                            <span class="info-box-text">Point de la journée Superette</span>
                            <span class="info-box-number">{{ number_format($sommeMontant, 0, ',', '.') }} CFA</span>
                            </div>
                        
                        </div>
                    
                    </div>
                @endif

                @if($role== 1 || $role==4 || $role==5)
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1 blink"><i class="fas fa-shopping-cart"></i></span>
            
                            <div class="info-box-content">
                            <span class="info-box-text">Point de la journée Poissonnerie</span>
                            <span class="info-box-number">{{ number_format($sommeMontantPoissonnerie, 0, ',', '.') }} CFA</span>
                            </div>
                        
                        </div>
                    
                    </div>
                @endif
                
            </div>
            

            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Les ventes de la journée</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                            <th>Client</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Total TTC</th>
                            <th>Montant Percu</th>
                            <th>Reliquat</th>
                            <th>Caissier</th>
                            <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($facturesAujourdhuiSuper as $facture )
                                @if($facture->produitType_id == 1 && $role== 2 || $role==3)
                                
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $facture->client_nom }}</td>
                                    <td><span class="right badge badge-success">Superette</span> </td>


                                    <td>{{ date('d/m/Y', strtotime($facture->date)) }}</td>

                                    <td>{{ $facture->montantFinal }}</td>

                                    <td>{{ $facture->montantPaye }}</td>
                                    <td><span class="right badge badge-info"><b>{{ $facture->montantPaye - $facture->montantFinal }}</b></span></td>
                                    <td><b>{{ $facture->user->name }}</b></td>
                                    <td>

                                        <a href="{{ route('facture.details', ['code' => $facture->code, 'date' => $facture->date]) }}"
                                            class="btn-sm btn-primary">Détail</a>
                                        @auth

                                            @if (auth()->user()->role_id == 1)
                                                <a href="#" class="btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#confirmationModal"
                                                    onclick="updateModal('{{ $facture->code }}')">Annuler</a>
                                            @endif

                                        @endauth

                                    </td>
                                </tr>
                                @endif
                            @endforeach

                            @foreach ($facturesAujourdhuiPoissonnerie as $facture )
                                @if($facture->produitType_id == 3 && $role== 5)
                                
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $facture->client_nom }}</td>
                                    <td><span class="right badge badge-warning">Poissonnerie</span> </td>

                                    <td>{{ date('d/m/Y', strtotime($facture->date)) }}</td>

                                    <td>{{ $facture->montantFinal }}</td>

                                    <td>{{ $facture->montantPaye }}</td>
                                    <td><span class="right badge badge-info"><b>{{ $facture->montantPaye - $facture->montantFinal }}</b></span></td>
                                    <td><b>{{ $facture->user->name }}</b></td>
                                    <td>

                                        <a href="{{ route('facture.details', ['code' => $facture->code, 'date' => $facture->date]) }}"
                                            class="btn-sm btn-primary">Détail</a>
                                        @auth

                                            @if (auth()->user()->role_id == 1)
                                                <a href="#" class="btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#confirmationModal"
                                                    onclick="updateModal('{{ $facture->code }}')">Annuler</a>
                                            @endif

                                        @endauth

                                    </td>
                                </tr>
                                @endif
                            @endforeach


                            @foreach ($facturesAujourdhui as $facture )
                                @if($role== 1 || $role==4)
                                
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $facture->client_nom }}</td>
                                    <td>
                                        @if($facture->produitType_id == 3)
                                        <span class="right badge badge-warning">Poissonnerie</span>
                                        @else
                                        <span class="right badge badge-success">Superette</span>
                                        @endif
                                    </td>

                                    <td>{{ date('d/m/Y', strtotime($facture->date)) }}</td>

                                    <td>{{ $facture->montantFinal }}</td>

                                    <td>{{ $facture->montantPaye }}</td>
                                    <td><span class="right badge badge-info"><b>{{ $facture->montantPaye - $facture->montantFinal }}</b></span></td>
                                    <td><b>{{ $facture->user->name }}</b></td>
                                    <td>

                                        <a href="{{ route('facture.details', ['code' => $facture->code, 'date' => $facture->date]) }}"
                                            class="btn-sm btn-primary">Détail</a>
                                        @auth

                                            @if (auth()->user()->role_id == 1)
                                                <a href="#" class="btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#confirmationModal"
                                                    onclick="updateModal('{{ $facture->code }}')">Annuler</a>
                                            @endif

                                        @endauth

                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </section>


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

<style>
    @keyframes blink {
    50% {
        opacity: 0;
    }
}

.blink {
    animation: blink 1s infinite;
}

</style>

@endsection
