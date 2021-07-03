@extends('layouts.app')

@section('title')
@parent
&middot; Contact Us
@stop

@section('css')
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
@stop

@section('content')
<div class="row">
    <div class="col-1">
    </div>

    <div class="col-10 col-md-5">

        <div id="textContact" class="container">
            <p></p>
            <p> Para ajuda com a sua conta PayPal ou efectuar pagamentos com PayPal é melhor contactar o PayPal
                Customer Support directamente. Pode contactar PayPal Customer Support ao escolher o botão em baixo, ou
                ao seleccionar Help & Contact no fundo de qualquer
                página do PayPal website.</p>
        </div>
    </div>


    <div class="col-10 offset-1 col-md-5 offset-md-0">
        <p></p>
         @include('partials.flash-message') 
       
        <!--Form with header-->
        <form method="post" action="{{ route('contact.store') }}">
            {{ csrf_field() }}
            <div class="card rounded-0">

                <div class="text-center">
                    <h2>Contate-nos</h2>
                </div>
                <div class="card-body p-3">

                    <!--Body-->
                    <div class="form-group">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="nome" name="name" placeholder="Nome" required>
                        </div>
                        @if ($errors->has('name'))
                        <span class="error" style="color:red">
                            {{ $errors->first('name') }}
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="input-group mb-2">
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Exemplo@gmail.com" required>

                        </div>
                        @if ($errors->has('email'))
                        <span class="error" style="color:red">
                            {{ $errors->first('email') }}
                        </span>
                        @endif

                    </div>

                    <div class="form-group">
                        <div class="input-group mb-2">
                            <textarea class="form-control" name="msg" placeholder="Escrever mensagem..."
                                required></textarea>

                        </div>
                        @if ($errors->has('msg'))
                        <span class="error" style="color:red">
                            {{ $errors->first('msg') }}
                        </span>
                        @endif
                    </div>

                    <div class="text-center">
                        <input id="botoesRegister" type="submit" value="Enviar" class="btn btn-info btn-block">
                    </div>
                </div>

            </div>
        </form>
        <!--Form with header-->

    </div>

    <div class="col-1">
    </div>
</div>
</div>

<img src="Banner_01.png" class="img-fluid" alt="eBaw banner">
@stop
