@extends('layouts.appNoNav')

@section('css')
    <link rel="stylesheet" href="{{url('css/register.css')}}">
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
@stop

@section('js')
@parent
<script src="{{ URL::asset ('/js/validators.js') }}" defer></script>  
@stop


@section('content')
    <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- REGISTER BODY -/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
    <div class="container-fluid text-center">
        <div class="row">
            <div class="col-md-4">
                <!--1 of 3 Column-->
            </div>
            <div class="col-md-4">
                <p></p>
                <div class="form-signin">
                    <img src="logotipo.png" class="mb-4 img-fluid mx-auto" alt="eBaw logo">
                    <div>
                        <p id="textRecuperarPalavraPasse">Se já tem conta
                            <a href="{{ route('login') }}" style="color:#7bc411">Inicie a sessão
                            </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!--3 of 3 Column-->
            </div>
        </div>


        <div class="row">
            <div class="col-md-3">
                <!--1 of 3 Column-->
            </div>
            <div class="col-md-6">
                <!-- Default form register -->
                <form class="text-center border border-light p-3" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    <h1 id="textRecuperarPalavraPasse" class="h4 mb-3">Registo</h1>
                    <div class="form-row mb-3">
                        <div class="col">
                            <!-- Name -->
                            @if ($errors->has('name'))
                            <span class="error">
                                {{ $errors->first('name') }}
                            </span>
                            @endif
                            <input type="text" id="defaultRegisterFormFirstName" class="form-control" name="name" value="{{ old('name') }}" placeholder="Nome *" required autofocus>
                        </div>
                    </div>

                    <!-- username -->
                    @if ($errors->has('username'))
                        <span class="error">
                            {{ $errors->first('username') }}
                        </span>
                    @endif
                    <input type="text" id="username" class="form-control mb-3" placeholder="Username *" name="username" value="{{ old('username') }}"
                        aria-describedby="defaultRegisterFormUsernameHelpBlock" required>

                    <!-- E-mail -->
                    @if ($errors->has('email'))
                        <span class="error">
                            {{ $errors->first('email') }}
                        </span>
                    @endif
                    <input type="email" id="defaultRegisterFormEmail" class="form-control mb-3" name="email" value="{{ old('email') }}" placeholder="E-mail *" required>

                    <!-- Password -->
                    <input type="password" id="defaultRegisterFormPassword" class="form-control" name="password" placeholder="Palavra-passe *"
                        aria-describedby="defaultRegisterFormPasswordHelpBlock" required>
                        @if ($errors->has('password'))
                        <span class="error">
                            {{ $errors->first('password') }}
                        </span>
                        @endif
                    <small id="defaultRegisterFormPasswordHelpBlock" class="form-text text-muted mb-4">
                        Pelo menos 8 carateres com letras e digitos
                    </small>

                    <!-- Confirmar Password -->
                    <input type="password" id="password-confirm" class="form-control mb-3" name="password_confirmation" placeholder="Confirmar palavra-passe *"
                        aria-describedby="defaultRegisterFormPasswordHelpBlock" required>

                    <!-- Phone number -->
                    @if ($errors->has('phone_number'))
                    <span class="error">
                        {{ $errors->first('phone_number') }}
                    </span>
                    @endif
                    <input type="tel" id="defaultRegisterPhoneNumber" class="form-control mb-3" name="phone_number" value="{{ old('phone_number') }}" placeholder="Número de telefone *"
                        aria-describedby="defaultRegisterFormPhoneHelpBlock" required>

                    <!-- Morada -->
                    @if ($errors->has('address'))
                    <span class="error">
                        {{ $errors->first('address') }}
                    </span>
                    @endif
                    <input type="text" id="defaultRegisterAddress" class="form-control mb-3" name="address" value="{{ old('address') }}" placeholder="Morada *"
                        aria-describedby="defaultRegisterFormAddressHelpBlock" required>

                    <!-- CódigoPostal -->
                    @if ($errors->has('id_postal'))
                    <span class="error">
                        {{ $errors->first('id_postal') }}
                    </span>
                    @endif
                    <input type="text" id="defaultRegisterPostal" class="form-control mb-3" name="id_postal" value="{{ old('id_postal') }}" placeholder="Código Postal(XXXX-XXX) *"
                        aria-describedby="defaultRegisterFormPostalHelpBlock" required>

                    

                    <!-- Data de Nascimento -->
                    @if ($errors->has('birth_date'))
                    <span class="error">
                        {{ $errors->first('birth_date') }}
                    </span>
                    @endif
                    <input type="date" id="defaultRegisterBirthdate" class="form-control mb-3" name="birth_date" value="{{ old('birth_date') }}" placeholder="Data de Nascimento(YYYY/MM/DD) *"
                        aria-describedby="defaultRegisterFormBirthdateHelpBlock" required>


                    <!-- Sign up button -->
                    <button class="btn btn-primary my-4 btn-block" type="submit">Registar</button>

                    <!-- Social register -->
                    <p id="textRecuperarPalavraPasse">ou registe-se com:</p>
                    <button id="botoesRegisterGoogle" class="btn btn-lg btn-primary btn-block " type="submit" style="padding:0;">
                        <img src="btn_google_signin_dark_normal_web@2x.png" alt="google API image" style="max-height: 38px;">
                    </button>
                    <hr>

                    <!-- Terms of service -->
                    <p id="textRecuperarPalavraPasse">Ao clickar em
                        <em>Registar</em> está a concordar com  
                        <a href="" target="_blank" style="color:#7bc411">as regras do serviço</a>

                </form>
                <!-- Default form register -->


            </div>
            <div class="col-md-3">
                <!--3 of 3 Column-->
            </div>
        </div>


    </div>

    <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- REGISTER BODY END -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->

    <img src="Banner_01.png" class="img-fluid" alt="eBaw banner">
@stop
