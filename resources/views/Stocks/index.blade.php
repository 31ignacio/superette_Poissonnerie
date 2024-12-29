@extends('layouts.master2')

@section('content')
    <div class="container">
        <div class="row">

            @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 5 || auth()->user()->role_id == 4)

                <div class="col-md-3 col-sm-6 col-md-3">
                    <div class="info-box">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-plus"></i></span>

                        <div class="info-box-content">
                            <a href="{{ route('stock.entrerPoissonerie') }}">
                                <span class="info-box-text">Entrés de stocks</span>
                                <span class="info-box-number">
                                    (Poissonnerie)
                                    <small></small>
                                </span>
                            </a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            @endif
            @if(auth()->user()->role_id != 5 )
                <div class="col-md-3 col-sm-6 col-md-3">
                    <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-plus"></i></span>

                        <div class="info-box-content">
                            <a href="{{ route('stock.entrer') }}">
                                <span class="info-box-text">Entrés de stocks</span>
                                <span class="info-box-number">
                                    (Détail)
                                    <small></small>
                                </span>
                            </a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            
                <div class="col-md-3 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-minus"></i></span>

                        <div class="info-box-content">
                            <a href="{{ route('stock.sortie') }}">

                                <span class="info-box-text">Sorties de stocks</span>
                                <span class="info-box-number">(Détail)</span>
                            </a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-box"></i></span>

                        <div class="info-box-content">
                            <a href="{{ route('stock.actuel') }}">

                                <span class="info-box-text">Stocks actuels</span>
                                <span class="info-box-number">(Détail)</span>
                            </a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            @endif
           
        </div>
    </div>
    
@endsection
