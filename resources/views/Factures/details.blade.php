@extends('layouts.master2')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <!-- Main content -->
                    <div class="invoice p-3 mb-3" id="my-table">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h5>
                                    <i class="fas fa-globe"></i> <b>Leoni's</b>.
                                    <small class="float-right">Date: {{ date('d/m/Y', strtotime($date)) }}
                                    </small>
                                </h5>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row --><br>
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                @php
                                    $infosAffichees = false;
                                @endphp

                                @foreach ($factures as $facture)
                                    @if ($facture->date == $date && $facture->code == $code)
                                        @if (!$infosAffichees)
                                            <address>
                                                @if ($facture->client == null)
                                                    <strong>Client: Client</strong><br>
                                                @else
                                                    <strong>Client : {{ $facture->client_nom }}</strong><br>
                                                @endif

                                                {{-- N° Ifu :  {{$facture->client->ifu}}<br> --}}

                                            </address>

                                            @php
                                                $infosAffichees = true; // Marquer que les informations ont été affichées
                                            @endphp
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">

                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">

                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Quantité</th>
                                            <th>Produits</th>
                                            <th>Prix</th>
                                            <th>Totals</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($factures as $facture)
                                            @if ($facture->date == $date && $facture->code == $code)
                                                <tr>
                                                    <td>{{ $facture->quantite }}</td>
                                                    <td>{{ $facture->produit }}</td>
                                                    <td>{{ $facture->prix }}</td>
                                                    <td>{{ $facture->total }}</td>
                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-6">

                            </div>
                            <!-- /.col -->
                            <div class="col-sm-12 col-md-6">

                                <div class="table-responsive">
                                    <table class="table">

                                        @php
                                            $infosAffichees = false;
                                        @endphp

                                        @foreach ($factures as $facture)
                                            @if ($facture->date == $date && $facture->code == $code)
                                                @if (!$infosAffichees)
                                                    <tr>
                                                        <th style="width:50%">Total HT:</th>
                                                        <td>{{ $facture->totalHT }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total TVA</th>
                                                        <td>{{ $facture->totalTVA }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total TTC</th>
                                                        <td>{{ $facture->totalTTC }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Montant perçu </th>
                                                        <td>{{ $facture->montantPaye }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Reliquat </th>
                                                        <td>{{ $facture->montantPaye - $facture->montantFinal }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Remise (%) </th>
                                                        <td>{{ $facture->reduction }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Montant payé </th>
                                                        <td>{{ $facture->montantFinal }}</td>
                                                    </tr>
                                                    @php
                                                        $infosAffichees = true; // Marquer que les informations ont été affichées
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach

                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                    </div>

                    <div class="row no-print mb-5">
                        <div class="col-12">
                           
                            @php
                                $infosAffichees = false;
                            @endphp

                            @foreach ($factures as $facture)
                                @if ($facture->date == $date && $facture->code == $code)
                                    @if (!$infosAffichees)
                                        <div class="row no-print">
                                            <div class="col-12">
                                                
                                                    <a href="{{ route('facture.impression', ['code' => $facture->code, 'date' => $facture->date]) }}"
                                                      class="btn btn-success float-right" target="_blank">
                                                      <i class="fas fa-print"></i> Imprimer
                                                    </a>

                                                <button onclick="generatePDF()" type="button"
                                                    class="btn btn-danger float-right" style="margin-right: 5px;"
                                                    id="boutonPDF">
                                                    <i class="fas fa-download"></i> Facture PDF
                                                </button>
                                            </div>
                                        </div>

                                        @php
                                            $infosAffichees = true; // Marquer que les informations ont été affichées
                                        @endphp
                                    @endif
                                @endif
                            @endforeach

                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>


  <script>
      // Définir la fonction generatePDF à l'extérieur de la fonction click
      function generatePDF() {
          // Récupérer le contenu du tableau HTML
          var element = document.getElementById('my-table');

          // Options pour la méthode html2pdf
          var timestamp = new Date().getTime(); // Obtenez un timestamp unique
          var filename = 'Facture_' + timestamp + '.pdf'; // Nom du fichier avec le timestamp

          var opt = {
              margin: 1,
              filename: filename, // Utilisez le nom de fichier dynamique
              image: {
                  type: 'jpeg',
                  quality: 0.98
              },
              html2canvas: {
                  scale: 2
              },
              jsPDF: {
                  unit: 'in',
                  format: 'letter',
                  orientation: 'portrait'
              }
          };

          // Utiliser la méthode html2pdf pour générer le PDF à partir du contenu du tableau HTML
          html2pdf().from(element).set(opt).save();
      }
  </script>

  
@endsection
