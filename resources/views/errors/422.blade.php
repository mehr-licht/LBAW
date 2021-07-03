@extends('layouts.app')

@section('title')
Unprocessable Entity
@stop

@section('css')
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
@stop

@section('content')	
        
        <div id="page-card" class="container card-container font-content not-found-container" style="margin-top:50px">
          <header>
              <h1>422 - Unprocessable Entity</h1>
          </header>

          <div class="not-found-body">
               <?php if($product>0) { ?>

              <h1>{{'O produto '.$product.' está '}}
                  {{'O produto '.$product. ' está ' . $status=='' ? 'indisponível':$status}}
              </h1>
                <?php } else { ?>
                <h1>Licitação Inválida (valor baixo ou já terminado)</h1>
                <?php } ?>
              <p>
                  Clique <a href="{{ url()->previous() }}" class="btn btn-default">Aqui</a>para voltar atrás
              </p>
          </div>
        </div>
@stop
