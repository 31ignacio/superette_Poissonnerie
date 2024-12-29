@extends('layouts.master2')

@section('content')
    <section class="content">

        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <form class="settings-form" method="POST" action="{{ route('emplacement.update', $emplacement->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" class="form-control" style="border-radius:10px;" name="emplacement"
                                   value="{{$emplacement->nom}}" id="emplacement">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary" style="margin-top:10px;border-radius:10px;"
                            onclick="emplacement()"><i class="fas fa-plus-circle"></i>Ajouter</button>

                    </div>
                </form>
            </div>


        </div>


    </section>
@endsection
