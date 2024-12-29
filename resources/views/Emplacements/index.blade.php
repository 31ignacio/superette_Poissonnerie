@extends('layouts.master2')

@section('content')


<section class="content">

    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" class="form-control" placeholder="Enter ..." style="border-radius:10px;" name="emplacement" id="emplacement">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-sm btn-primary" style="margin-top:10px;border-radius:10px;" onclick="emplacement()"><i class="fas fa-plus-circle"></i>Ajouter</button>   

            </div>
        </div>

        <div class="col-md-7">
            <div id="msg200"></div>

            @if (Session::get('success_message'))
            <div class="alert alert-success">{{ Session::get('success_message') }}</div>
            <script>
              setTimeout(() => {
                  document.getElementById('success-message').remove();
              }, 3000);
          </script>
        @endif


             <!-- /.card-header -->
             <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Emplacement</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($emplacements as $emplacement )
                    
                    <tr>
                      <td>{{$emplacement->nom}}</td>
                      <td> 
                        <a class="btn-sm btn-warning" href="{{ route('emplacement.edit', $emplacement->id) }}"><i class="fas fa-edit"></i></a>
                        <a class="btn-sm btn-danger" href="{{ route('emplacement.delete', $emplacement->id) }}"><i class="fas fa-trash-alt"></i></a>
                     </td>
                                         
                     </tr>
                     @endforeach
                  </tbody>
                </table>
                <br>
                 {{-- LA PAGINATION --}}
                 <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        @if ($emplacements->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo; Précédent</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $emplacements->previousPageUrl() }}" rel="prev" aria-label="Précédent">&laquo; Précédent</a>
                            </li>
                        @endif
                
                        @if ($emplacements->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $emplacements->nextPageUrl() }}" rel="next" aria-label="Suivant">Suivant &raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">Suivant &raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
                
              </div>
              <!-- /.card-body -->
             
        </div>


    </div>


</section>


<script>
  function emplacement() {
      // $("#remboursementBtn").click(function() {
          // Récupérer l'ID du client depuis la page
          var emplacement = $("#emplacement").val();
              //alert(ref)
          // Récupérer le jeton CSRF depuis la balise meta
          var csrfToken = $('meta[name="csrf-token"]').attr('content');

          // Envoyer l'ID du client au contrôleur Laravel via une requête AJAX
          $.ajax({
              type: 'POST',
              url: "{{ route('emplacement.store') }}", // Remplacez "/votre-route" par la route pertinente de votre application
              data: {
                  _token: csrfToken,emplacement
              },
              success: function(response) {
                  // Gérer la réponse du serveur ici (par exemple, afficher un message de confirmation)
                  if (parseInt(response) == 200 || parseInt(response) == 500) {

                      parseInt(response) == 500 ? ($("#msg200").html(`<div class='alert alert-danger text-center' role='alert'>
                      <strong>Une erreur s'est produite</strong> veuillez réessayez.

                      </div>`)) : ($('#msg200').html(`<div class='alert alert-success text-center' role='alert'>
                      <strong> Emplacement ajouté avec succès  </strong>

                      </div>`));
                  }

                  var url = "{{ route('emplacement.index') }}"
                  if (response == 200) {
                      setTimeout(function() {
                          window.location = url
                      }, 1000)
                  } else {
                      $("#msg200").html(response);

                  }
              },

          });
      // });
  };
</script>


@endsection
