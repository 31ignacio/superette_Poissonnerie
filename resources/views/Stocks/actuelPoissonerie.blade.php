@extends('layouts.master2')

@section('content')


<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h1 class="card-title">Stocks actuels(Détails)</h1>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Produits</th>
                  <th>Quantité</th>
                  <th>Types</th>
                </tr>
                </thead>
                <tbody>
                 

                  @foreach ($produits as $produit)
                        @if($produit->produitType_id == 3)
                            <tr>
                                    <td>{{ $produit->libelle }}</td>
                                    <td>
                                        {{ $produit->stock_actuel }}
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">Poissonerie</span>
                                    </td>
                            </tr>
                        @endif
                    @endforeach
            
                </tbody>
              </table>
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
  </section>

  @endsection

