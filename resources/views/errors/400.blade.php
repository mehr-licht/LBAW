@extends('layouts.app')

@section('title')
Bad Request
@stop

@section('css')
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
@stop

@section('content')	
        
        <div id="page-card" class="container card-container font-content not-found-container" style="margin-top:50px">
        <header>
            <?php if($product>0) { ?>
            <h1>400 - Product {{$product}} is not avaliable</h1>
            <?php } else { ?>
            <h1>400 - Bad Request</h1> <?php } ?>
        </header>
        <div class="not-found-body">

            <p>
                Clique <a href="{{ url()->previous() }}" class="btn btn-default">Aqui</a>para voltar atrás
            </p>
        </div>
        </div>
@stop

