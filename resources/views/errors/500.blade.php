@extends('layouts.app')

@section('title')
Internal Server Error
@stop

@section('css')
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
@stop

@section('content')	
        
        <div id="page-card" class="container card-container font-content not-found-container" style="margin-top:50px">
            <header>
                <h1>500 - Internal Server Error</h1>
            </header>
      
            <div class="not-found-body">
  
                <p>
                    Clique <a href="{{ url()->previous() }}" class="btn btn-default">Aqui</a>para voltar atrás
                </p>
            </div>
        </div>
        
@stop

