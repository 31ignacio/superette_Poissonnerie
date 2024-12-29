@extends('layouts.master2')

@section('content')

@if (Session::get('success_message'))
        <div class="alert alert-success" id="success-message">{{ Session::get('success_message') }}</div>
        
    @endif


    

    <div class="row">
        <div class="col-md-4"></div>

        <div class="col-md-4">
            <!-- general form elements -->
            <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Editer un fournisseur</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="settings-form" method="POST" action="{{ route('fournisseur.update',$fournisseur->id) }}">
                @csrf
                @method('PUT')
                            <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="societe">Raison sociale</label>
                            <input type="text" class="form-control" id="societe" name="societe" value="{{ $fournisseur->raisonSociale }}" required style="border-radius:10px;">
                        
                        {{-- Affiche les erreur sous le input (le @error prend le name du input) --}}
                        @error('societe')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="societe">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="{{ $fournisseur->nom }}" required style="border-radius:10px;">
                        
                            {{-- Affiche les erreur sous le input (le @error prend le name du input) --}}
                            @error('nom')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                   
                    <div class="row">

                        <div class="col-md-12">
                            <label for="prenom">Téléphone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone" value="{{ $fournisseur->telephone }}" required style="border-radius:10px;">
                       
                            {{-- Affiche les erreur sous le input (le @error prend le name du input) --}}
                            @error('telephone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    
                        <div class="col-md-12">
                            <label for="ville">Ville</label>
                            <input type="text" class="form-control" id="ville" name="ville" value="{{ $fournisseur->ville }}" required style="border-radius:10px;">

                            @error('ville')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        
                    </div>
                
                
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                <button type="submit" class="btn btn-sm btn-secondary" style="border-radius:10px;">Envoyer</button>
                </div>
            </form>
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-4"></div>

    </div>

@endsection
  