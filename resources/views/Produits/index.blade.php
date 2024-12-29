@extends('layouts.master2')

@section('content')


<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

            <a href="#" type="button" class="btn bg-gradient-primary" data-toggle="modal" data-target="#modal-xl">
              Ajouter Produit
            </a><br><br><br>
          
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Liste des produits</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Code</th>
                  <th>Produits</th>
                   <th>Types</th>
                  <th>Emplacements</th>
                  <th>Actions</th>

                </tr>
                </thead>
                <tbody>
                    @forelse ($produits as $produit)

                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $produit->code }}</td>
                  <td>{{ $produit->libelle }}</td>
                  <td>
                    @if( $produit->produitType_id == 1)
                              
                    <span class="badge badge-success">Détails</span>

                    @elseif( $produit->produitType_id ==3 )
                              
                    <span class="badge badge-warning">Poissonerie</span>

                    @else
                          <span class="badge badge-primary">Gros</span>
                    @endif
                  
                  </td>
                  <td>{{ $produit->emplacement->nom}}</td>

                  <td>
                    {{-- <a class="btn-sm btn-info" href="{{ route('produit.detail', $produit->id) }}"><i class="fas fa-eye"></i></a> --}}
                    <a class="btn-sm btn-primary mx-1" href="#" data-toggle="modal" data-target="#showEntree{{ $loop->iteration }}">
                      <i class="fas fa-eye"></i> 
                  </a>

                    <a href="#!" data-toggle="modal" data-target="#editEntry{{ $loop->iteration }}" class="btn-sm btn-warning mx-1"><i class="fas fa-edit"></i></a>

                    <form action="{{ route('produit.delete', ['produit' => $produit->id]) }}" method="POST" style="display: inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                          <i class="fas fa-trash-alt"></i>
                      </button>
                  </form>
                  
                  </td>
                </tr>
              @empty
                <tr>
                  <td class="cell" colspan="3">Aucun produit ajoutés</td>
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

    {{-- Modal pour enregistrer un produit --}}
    <div class="modal fade" id="modal-xl">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Nouveau produit</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          
            <form action="{{ route('produit.store') }}" method="POST">
                @csrf

              <div class="row">
                  <!-- Produit -->
                <div class="mb-3 col-md-6">
                  <label for="libelle" class="form-label">Produit</label>
                  <input type="text" 
                          class="form-control" 
                          id="libelle" 
                          name="libelle" 
                          placeholder="Entrez le nom du produit" 
                          value="{{ old('libelle') }}" 
                          style="border-radius: 10px;" 
                          required>
                </div>
                
                  <!-- Quantité -->
                <div class="mb-3 col-md-3">
                  <label for="quantite" class="form-label">Quantité</label>
                  <input type="text" 
                          class="form-control" 
                          id="quantite" 
                          name="quantite" 
                          placeholder="Entrez la quantité" 
                          value="{{ old('quantite') }}" 
                          min="0" 
                          style="border-radius: 10px;" 
                          required>
                </div>
                
                  <!-- Prix -->
                <div class="mb-3 col-md-3">
                  <label for="prix" class="form-label">Prix</label>
                  <input type="number" 
                          class="form-control" 
                          id="prix" 
                          name="prix" 
                          placeholder="Entrez le prix" 
                          value="{{ old('prix') }}" 
                          min="0" 
                          step="0.01" 
                          style="border-radius: 10px;" 
                          required>
                </div>
              </div>
                
              <div class="row">

                <div class="mb-3 col-md-6">
                    <label for="ifu" class="form-label">Produit type</label>
                    <select name="produitType" id="produitType" class="form-control" style="border-radius: 10px;" required>
                        <option></option>

                            @foreach ($produitTypes as $produitType )
                                <option value="{{$produitType->id}}">{{$produitType->produitType}}</option>

                            @endforeach
                    </select>                            
                </div>


                <div class="mb-3 col-md-6">
                    <label for="ifu" class="form-label">Fournisseurs</label>
                    <select name="fournisseur" id="fournisseur" class="form-control" style="border-radius: 10px;" required>
                            <option></option>
                            @foreach ($fournisseurs as $fournisseur )
                                <option value="{{$fournisseur->id}}">{{$fournisseur->nom}}</option>

                            @endforeach
                    </select>                            
                </div>

              </div>

              <div class="row">

                <div class="mb-3 col-md-6">
                    <label for="ifu" class="form-label">Catégories</label>
                    <select name="categorie" id="categorie" class="form-control" style="border-radius: 10px;" required>
                        <option></option>

                            @foreach ($categories as $categorie )
                                <option value="{{$categorie->id}}">{{$categorie->categorie}}</option>

                            @endforeach
                    </select>                            
                </div>

                <div class="mb-3 col-md-6">
                    <label for="emplacement" class="form-label">Emplacement</label>
                    <select name="emplacement" id="emplacement" class="form-control" style="border-radius: 10px;" required>
                        <option></option>

                            @foreach ($emplacements as $emplacement )
                                <option value="{{$emplacement->id}}">{{$emplacement->nom}}</option>
                            @endforeach
                    </select>
                </div>

              </div>

              <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="dateReception" class="form-label">Date reception</label>
                    <input type="date" class="form-control" required="true" id="dateReception" name="dateReception" value="{{old('dateReception')}}"
                        style="border-radius: 10px;" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label for="dateExpiration" class="form-label">Date expiration</label>
                    <input type="date" class="form-control" required="true" id="dateExpiration" name="dateExpiration" value="{{old('dateExpiration')}}"
                        style="border-radius: 10px;" required>
                </div>

              </div>


              <div class="modal-footer justify-content-between">
                <span  data-dismiss="modal">.</span>
                <button type="submite" class="btn btn-primary">Enregistrer</button>
              </div>

            </form>

          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
   
      {{-- Modifier produit --}}
    @foreach ($produits as $produit)
      <div class="modal fade" id="editEntry{{ $loop->iteration }}">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Editer le produit</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('produit.update', ['produit'=>$produit->id]) }}" method="POST">
                  @csrf
                  @method('POST')

                <div class="row">

                
                  <div class="mb-3 col-md-6">
                      <label for="libelle" class="form-label">Produit</label>
                      <input type="text" class="form-control" id="libelle" name="libelle" value="{{ $produit->libelle }}" style="border-radius: 10px;" required>
                  </div>

                  

                  <!-- Prix -->
                  <div class="mb-3 col-md-6">
                      <label for="prix" class="form-label">Prix</label>
                      <input type="number" class="form-control" id="prix" name="prix" value="{{ $produit->prix }}" required style="border-radius: 10px;">
                  </div>
                </div>

                <div class="row">
                  <!-- Produit type -->
                  <div class="mb-3 col-md-6">
                      <label for="produitType" class="form-label">Type de produit</label>
                      <select name="produitType" id="produitType" class="form-control" required style="border-radius: 10px;">
                          @foreach ($produitTypes as $produitType)
                              <option value="{{ $produitType->id }}" {{ $produit->produitType_id == $produitType->id ? 'selected' : '' }}>
                                  {{ $produitType->produitType }}
                              </option>
                          @endforeach
                      </select>
                  </div>

                  <!-- Fournisseur -->
                  <div class="mb-3 col-md-6">
                      <label for="fournisseur" class="form-label">Fournisseur</label>
                      <select name="fournisseur" id="fournisseur" class="form-control" required style="border-radius: 10px;">
                          @foreach ($fournisseurs as $fournisseur)
                              <option value="{{ $fournisseur->id }}" {{ $produit->fournisseur_id == $fournisseur->id ? 'selected' : '' }}>
                                  {{ $fournisseur->nom }}
                              </option>
                          @endforeach
                      </select>
                  </div>
                </div>

                <div class="row">
                  <!-- Catégorie -->
                  <div class="mb-3 col-md-6">
                      <label for="categorie" class="form-label">Catégorie</label>
                      <select name="categorie" id="categorie" class="form-control" required style="border-radius: 10px;">
                          @foreach ($categories as $categorie)
                              <option value="{{ $categorie->id }}" {{ $produit->categorie_id == $categorie->id ? 'selected' : '' }}>
                                  {{ $categorie->categorie }}
                              </option>
                          @endforeach
                      </select>
                  </div>

                  <!-- Emplacement -->
                  <div class="mb-3 col-md-6">
                      <label for="emplacement" class="form-label">Emplacement</label>
                      <select name="emplacement" id="emplacement" class="form-control" required style="border-radius: 10px;">
                          @foreach ($emplacements as $emplacement)
                              <option value="{{ $emplacement->id }}" {{ $produit->emplacement_id == $emplacement->id ? 'selected' : '' }}>
                                  {{ $emplacement->nom }}
                              </option>
                          @endforeach
                      </select>
                  </div>
                </div>

                <div class="row">
                  <!-- Date de réception -->
                  <div class="mb-3 col-md-6">
                      <label for="dateReception" class="form-label">Date de réception</label>
                      <input type="date" class="form-control" id="dateReception" name="dateReception" value="{{ old('dateReception', $produit->dateReception) }}" style="border-radius: 10px;" required>
                  </div>
          
                  <!-- Date d'expiration -->
                  <div class="mb-3 col-md-6">
                      <label for="dateExpiration" class="form-label">Date d'expiration</label>
                      <input type="date" class="form-control" id="dateExpiration" name="dateExpiration" value="{{ old('dateExpiration', $produit->dateExpiration) }}" style="border-radius: 10px;" required>
                  </div>
                </div>

                  <div class="modal-footer">
                      
                      <button type="submit" class="btn btn-primary">Modifier</button>
                  </div>
              </form>
            </div>
          
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    @endforeach

      {{-- Details d"un produit --}}
    @foreach ($produits as $produit)
      <div class="modal mt-5" id="showEntree{{ $loop->iteration }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
          <div class="modal-dialog" role="document" style="max-width: 800px; margin: auto;">
            <div class="modal-content"
              style="border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
              <div class="modal-header"
                  style="background-color: hsl(246, 60%, 37%);; color: white; padding: 10px 15px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                  <h5 class="modal-title text-white" id="exampleModalLabel">Détails du produit:  <b>{{ $produit->libelle }}</b></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                      onclick="closeModal({{ $loop->iteration }})"
                      style="background: transparent; border: none; font-size: 1.5em; color: white;">
                      &times;
                  </button>
              </div>

              <div class="modal-body" style="padding: 15px;">


                <div id="productDetails">
                  <div class="card col-sm-12 col-md-12">
                  
                    <div class="card-body table-responsive">
                      <table  class="table table-bordered table-striped">
                        <tbody>
                          <tr>
                            <th>Code</th>
                            <td>{{ $produit->code }}</td>
                          </tr>

                          <tr>
                            <th>Produit</th>
                            <td>{{ $produit->libelle }}</td>
                          </tr>

                          <tr>
                            <th>Produit type</th>
                            <td>{{ $produit->produitType->produitType }}</td>
                          </tr>

                          <tr>
                            <th>Emplacement</th>
                            <td>{{ $produit->emplacement->nom }}</td>
                          </tr>

                          <tr>
                              <th>Catégorie</th>
                              <td>{{ $produit->categorie->categorie }}</td>
                          </tr>

                          <tr>
                            <th>Fournisseur</th>
                            <td>{{ $produit->fournisseur->nom }}</td>
                          </tr>

                          <tr>
                            <th>Date reception</th>
                            <td>{{ date('d/m/Y', strtotime($produit->dateReception)) }}</td>
                          </tr>

                          <tr>
                            <th>Date expiration</th>
                            <td style="color:red;">{{ date('d/m/Y', strtotime($produit->dateExpiration)) }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div>


            </div>
          </div>
      </div>
    @endforeach

@endsection
