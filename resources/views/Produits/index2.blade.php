@extends('layouts.master2')

@section('content')


<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

            <a href="{{ route ('produit.create')}}" class="btn  bg-gradient-primary">Ajouter</a><br><br>


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
          

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Liste des produits en gros</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Code</th>
                  <th>Produits</th>
                  {{-- <th>Quantités</th> --}}
                  <th>Emplacements</th>
                  <th>Actions</th>

                </tr>
                </thead>
                <tbody>
                    @forelse ($produitsGros as $produit)

                <tr>
                  <td>{{ $produit->code }}</td>
                  <td>{{ $produit->libelle }}</td>
                  {{-- <td>{{ $produit->quantite }}</td> --}}
                  <td>{{ $produit->emplacement->nom}}</td>

                  <td>
                    <a class="btn-sm btn-info" href="{{ route('produit.detail', $produit->id) }}"><i class="fas fa-eye"></i></a>

                    <a class="btn-sm btn-warning" href="{{ route('produit.edit', $produit->id) }}"><i class="fas fa-edit"></i></a>

                    <a class="btn-sm btn-danger" href="{{ route('produit.delete', $produit->id) }}"><i class="fas fa-trash-alt"></i></a>
                  </td>
                </tr>
                @empty

                <tr>
                    <td class="cell text-center" colspan="5" >Aucun produit ajoutés</td>

                </tr>
                @endforelse


                </tfoot>
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

  <script>
    // Recherche de l'élément de message de succès
    var successMessage = document.getElementById('success-message');

    // Masquer le message de succès après 3 secondes (3000 millisecondes)
    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = 'none';
        }, 3000);
    }
</script>
  @endsection
