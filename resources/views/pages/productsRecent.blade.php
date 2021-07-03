@extends('layouts.app')

@section('title')
@parent
&middot; Recent Products
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
    
<div class="row container-fluid" style="margin-top:60px;">
    <div class="col-md-12 container">
        <div class="row ">
            
            @include('partials.filter')
            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8" id="list_products">

                <br/>
                <h6 class="text-muted not_center">A mostrar todos os resultados</h6>
                <br/>
                    <div class="row">
                        <div class="col-sm-8 col-xs-12 col-lg-8">
                    
                            <ul class="nav nav-tabs" id="myTab" role="tablist" style="background-color: #f8f9fa;">
                                <li class="nav-item">
                                <a class="nav-link" id="populares-tab" href="{{url('/products')}}"
                                        role="tab" aria-controls="populares" aria-selected="true">Populares</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link active" id="recentes-tab" data-toggle="tab" href="#recentes" role="tab"
                                        aria-controls="recentes" aria-selected="false"> + Recentes</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" id="aacabar-tab" href="{{url('/products/ending')}}" role="tab"
                                        aria-controls="aacabar" aria-selected="false"> A Acabar</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="caros-tab" href="{{url('/products/expensive')}}" role="tab"
                                        aria-controls="caros" aria-selected="false"> + Caros</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <br>
                
                
                <!-- PAGINATION -->
                <div class="row justify-content-center">
                    <div class="tab-content" id="myTabContent">
                            
                            
                            <div class="tab-pane fade show active" id="recentes" role="tabpanel" aria-labelledby="recentes-tab">
                                <section id="cards">
                                    @each('partials.product', $productsRecentes, 'product')
                                </section>                            
                            </div>
                    </div>

                    <div id="pagination">
                        <?php echo $productsRecentes->render(); ?>
                    </div>

                    
                </div> 
            </div>

        </div>
    </div>
</div>

@stop
