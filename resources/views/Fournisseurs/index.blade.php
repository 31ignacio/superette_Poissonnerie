@extends('layouts.master2')

@section('content')


<section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12">

            {{-- <a href="{{ route ('client.create')}}" class="btn  bg-gradient-primary">Ajouter client</a><br><br> --}}

           
            @if (Session::get('success_message'))
                <div class="alert alert-success">{{ Session::get('success_message') }}</div>
                <script>
                  setTimeout(() => {
                      document.getElementById('success-message').remove();
                  }, 3000);
              </script>
            @endif

          <div class="card">
            <div class="card-header">
              {{-- <h3 class="card-title">Liste des clients</h3> --}}
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Raison sociale</th>
                  <th>Nom</th>
                  <th>Téléphone</th>
                  <th>Ville</th>
                  <th>Actions</th>

                </tr>
                </thead>
                <tbody>
                    @forelse ($fournisseurs as $fournisseur)

                <tr>
                  <td>{{ $fournisseur->raisonSociale }} </td>
                  <td>{{ $fournisseur->nom }}</td>
                  <td>{{ $fournisseur->telephone }}</td>
                  <td>{{ $fournisseur->ville }}</td>
                  <td>
                    <a href="{{ route('fournisseur.detail', ['fournisseur' => $fournisseur->id]) }}" class="btn-sm btn-primary"><i class="fas fa-eye"></i></a>

                     <a class="btn-sm btn-warning" href="{{ route('fournisseur.edit', $fournisseur->id) }}"><i class="fas fa-edit"></i></a>
                    <a class="btn-sm btn-danger" href="{{ route('fournisseur.delete', $fournisseur->id) }}"><i class="fas fa-trash-alt"></i></a>
                 </td>

                 
                </tr>
                @empty

                <tr>
                    <td class="cell text-center" colspan="7">Aucun fournisseur ajouté</td>

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

  @endsection
