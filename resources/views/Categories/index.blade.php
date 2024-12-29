@extends('layouts.master2')

@section('content')


<section class="content">

    <div class="row">
            
        <div class="col-md-4">
            <form id="addUserForm" method="POST" action="{{ route('categorie.store') }}">
                @csrf
                
                    <div class="form-group">
                        <label>Ajouter une catégorie</label>
                        <input type="text" class="form-control" placeholder="Entrez la catégorie" style="border-radius: 10px;" id="categorie" name="categorie" required>
                    </div>
                
                    <button type="submit" class="btn btn-sm btn-primary" style="margin-top:8px;"><i class="fas fa-plus-circle"></i>Ajouter</button>   
    
            </form>
        </div>

        

        <div class="col-md-8">
          
            <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th>#</th>
                      <th>Catégorie</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                    <tbody>
                        @foreach ($categories as $categorie)
                        
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$categorie->categorie}}</td>
                            
                                <td> 
                                    <a href="#!" data-toggle="modal" data-target="#editEntry{{ $loop->iteration }}" class="btn-sm btn-warning mx-1"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('categorie.delete', $categorie->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            
                            </tr>
                        @endforeach

                    </tbody>
                </table>

                <br>
                 {{-- LA PAGINATION --}}
                 <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        @if ($categories->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-hidden="true">&laquo; Précédent</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $categories->previousPageUrl() }}" rel="prev" aria-label="Précédent">&laquo; Précédent</a>
                            </li>
                        @endif
                
                        @if ($categories->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $categories->nextPageUrl() }}" rel="next" aria-label="Suivant">Suivant &raquo;</a>
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
    
    {{-- Modifier categorie --}}
   @foreach ($categories as $categorie)
        <div class="modal fade" id="editEntry{{ $loop->iteration }}">
            <div class="modal-dialog modal-md">
                    <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">Editer la categorie</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                            <form class="settings-form" method="POST" action="{{ route('categorie.update',$categorie->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="categorie">Catégorie</label>
                                            <input type="text" class="form-control" id="categorie" value="{{ $categorie->categorie }}" name="categorie" required>
                                                @error('categorie')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                <button type="submit" class="btn btn-warning">Editer</button>
                                </div>
                            </form>
                    </div>
                    </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
   @endforeach


</section>

@endsection
