@extends('layouts.appNoNav')

@section('css')
    <link rel="stylesheet" href="{{url('/css/register.css')}}">
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
@stop

@section('content')
@include('partials.flash-message')
<div id="bodyIntro" class="container-fluid text-center">
        <div class="row">

            <div class="col-md-4">
            </div>

            <div class="col-md-4">
                <p></p>
                <form class="form-signin" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}                
                    <img src="logotipo.png" class="mb-4 img-fluid mx-auto" alt="eBaw logo">
                        @if($errors->any())
                            <h5 id="errorMessage">{{$errors->first()}}</h5>
                        @endif
                    <h1 id="textRecuperarPalavraPasse" class="h3 mb-3 font-weight-normal">Iniciar Sessão</h1>
                    <label for="inputEmail" class="sr-only">Email</label>
                    <input type="text" id="inputEmailUsername" class="form-control mb-3" placeholder="Email address ou username" required="" autofocus="" name="email">
                    
                    @if ($errors->has('email'))
                        <span  id="errorMessage" class="error">
                            {{ $errors->first('email') }}
                        </span>
                    @endif

                    
                    <label for="inputPassword" class="sr-only">Password</label>
                    <input type="password" id="inputPassword" class="form-control mb-3" placeholder="Password" required="" name="password">
                    
                    @if ($errors->has('password'))
                        <span id="errorMessage" class="error">
                            {{ $errors->first('password') }}
                        </span>
                    @endif

                    <a href="{{route('recovery')}}" style="color:#7bc411">Esqueceu-se da palavra passe? </a>
                    <div class="checkbox mb-3">
                        <label>
                            <input type="checkbox" value="remember-me" {{ old('remember') ? 'checked' : '' }}> <i id="textRecuperarPalavraPasse">Relembrar dados</i>
                        </label>
                    </div>
                    <button id="botoesRegister" class="btn btn-lg btn-primary btn-block " type="submit">Iniciar Sessão</button>
                    <div>
                        <h3 id="textRecuperarPalavraPasse" class="h3 font-weight-normal">ou</h3>
                    </div>
                    <button id="botoesRegisterGoogle" class="btn btn-lg btn-primary btn-block " type="submit" style="padding:0;">
                        <img src="btn_google_signin_dark_normal_web@2x.png" alt="google API image" style="max-height: 48px;">
                    </button>
                    <hr>
                    <div>
                        <p id="textRecuperarPalavraPasse">Se não tem conta <a href="{{ route('register') }}" style="color:#7bc411">Registe-se</a> </p>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
            </div>
        </div>
    </div>


    <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- REGISTER BODY END -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->

    <img src="Banner_01.png" class="img-fluid" alt="eBaw banner">

@stop
