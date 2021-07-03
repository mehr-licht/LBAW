@extends('layouts.app')

@section('title')
@parent
&middot; Online Shopping
@stop

@section('css')
    <link rel="stylesheet" href="{{url('/css/products.css')}}">
    <link rel="stylesheet" href="{{url('/css/global.css')}}">
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans');
    </style>
@stop

@section('title')
    eBaw &middot; online shopping
@stop


@section('content')

    
<div id="body-mainContent" class="container" style="margin-top:45px;"> 
    <!--main slideshow-->
    <div class="jumbotron">

        <div id="main_carousel" class="carousel slide" data-ride="carousel" data-interval="4000">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="row">
                
                <div class="col-12 col-lg-3 carousel_introduction">
                <h2>Veja o que está em alta na Categoria Artes</h2>
                    <a href="{{ url('/products') }}" class="btn btn-outline-primary">Compre agora 
                        <i class="fa fa-arrow-right" style="font-size: small;"></i>
                    </a>
                </div>
                
                @foreach(array_slice($arts, 0, 3)  as $art)
                    <div class="col-sm-4 col-lg-3">
                            <a href="{{ url('/products', [ $art->id ]) }}">
                                <div class="container">
                                    <div class="row">
                                        <img src="{{ $art->photo }}" alt="{{ $art->name_product }}">    
                                    </div>
                                    <div class="row item-info">
                                            <h5>{{ $art->name_product }}</h5>
                                            <p>{{ $art->final_value }}€</p>
                                    </div>
                                </div>  
                            </a>
                    </div>
                @endforeach

            </div>
          </div>

          <div class="carousel-item">
            <div class="row">
                <div class="col-12 col-lg-3 carousel_introduction">
                    <h2>Veja o que está em alta na Categoria Computers</h2>
                    <a href="{{ url('/products') }}" class="btn btn-outline-primary">Compre agora 
                        <i class="fa fa-arrow-right" style="font-size: small;"></i>
                    </a>
                </div>
                
                @foreach(array_slice($computers, 0, 3) as $computer)
                    <div class="col-sm-4 col-lg-3">
                        <a href="{{ url('/products', [ $computer->id ]) }}">
                            <div class="container">
                                <div class="row">
                                    <img src="{{ $computer->photo }}" alt="{{ $computer->name_product }}">    
                                </div>
                                <div class="row item-info">
                                        <h5>{{ $computer->name_product }}</h5>
                                        <p>{{ $computer->final_value }}€</p>
                                </div>
                            </div>  
                        </a>
                    </div>
                @endforeach
                
                
            </div>
          </div>

          <div class="carousel-item">
            <div class="row">
                <div class="col-12 col-lg-3 carousel_introduction">
                    <h2>Veja o que está em alta na Categoria Comics</h2>
                    <a href="{{ url('/products') }}" class="btn btn-outline-primary">Compre agora 
                        <i class="fa fa-arrow-right" style="font-size: small;"></i>
                    </a>
                </div>

                @foreach(array_slice($comics, 0, 3) as $comic)    
                    <div class="col-sm-4 col-lg-3">
                        <a href="{{ url('/products', [ $comic->id ]) }}">
                            <div class="container">
                                <div class="row">
                                    <img src="{{ $comic->photo }}" alt="{{ $comic->name_product }}">    
                                </div>
                                <div class="row item-info">
                                        <h5>{{ $comic->name_product }}</h5>
                                        <p>{{ $comic->final_value }}€</p>
                                </div>
                            </div>  
                        </a>
                    </div>
                @endforeach
                
            </div>
          </div>

        </div>
        <a class="carousel-control-prev" href="#main_carousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#main_carousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
        </div>
    </div>
    <!-- Slideshow -->

    <!-- Day Offers-->
    <div class="container">
        <div class="container daily_offer_header">
            <div class="row">
                <h3 class="col-auto daily_offers">
                    <a href="{{ url('/products') }}">
                        Ofertas do dia
                    </a>
                </h3>
                <div class="col-auto see_all">
                    <a href="{{ url('/products') }}">
                        Ver todos <i class="fa fa-arrow-right" style="font-size: small;"></i>
                    </a>
                </div>
            </div>
        </div>
        <div id="daily_offers_carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row">
                        
                        @foreach($dayOffersPlaneOnes as $dayOffersPlaneOne)
                            <div class="col-6 col-sm-4 col-lg-2">
                                <a href="{{ url('/products', [ $dayOffersPlaneOne->id ]) }}">
                                    <div class="container">
                                        <div class="row">
                                            <img src="{{ $dayOffersPlaneOne->photo }}" alt="{{ $dayOffersPlaneOne->name_product }}">    
                                        </div>
                                        <div class="row item-info">
                                                <h5>{{ $dayOffersPlaneOne->name_product }}</h5>
                                                <p>{{ $dayOffersPlaneOne->final_value }}€</p>
                                        </div>
                                    </div>  
                                </a>
                            </div>   
                        @endforeach

                    </div>
                </div>

                <div class="carousel-item">
                    <div class="row">
                        
                        @foreach($dayOffersPlaneTwos->take(6) as $dayOffersPlaneTwo)
                            <div class="col-6 col-sm-4 col-lg-2">
                                <a href="{{ url('/products', [ $dayOffersPlaneTwo->id ]) }}">
                                    <div class="container">
                                        <div class="row">
                                            <img src="{{ $dayOffersPlaneTwo->photo }}" alt="{{ $dayOffersPlaneTwo->name_product }}">    
                                        </div>
                                        <div class="row item-info">
                                                <h5>{{ $dayOffersPlaneTwo->name_product }}</h5>
                                                <p>{{ $dayOffersPlaneTwo->final_value }}€</p>
                                        </div>
                                    </div>  
                                </a>
                            </div>
                        @endforeach
                        
                    </div>
                </div>

                
            </div>
            <a class="carousel-control-prev" href="#daily_offers_carousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#daily_offers_carousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
    
@stop