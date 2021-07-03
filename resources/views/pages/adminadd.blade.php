@extends('layouts.app')

@section('title')
@parent
&middot; Add Asministrator
@stop

@section('css')
    <link rel="stylesheet" href="{{url('/css/global.css')}}">
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
    <link rel="stylesheet" href="{{url('/css/admin.css')}}">
@stop

@section('title')
    Admin
@stop

@section('scripts')
<script src="{{ URL::asset ('/js/admin.js') }}" defer></script>
<script src="{{ URL::asset ('/js/global.js') }}" defer></script>
@stop


@section('content')

    <div class="container w-75 mb-4" id="admin_main_container" style="margin-top:60px;">
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="background-color: #f8f9fa;">
            <li class="nav-item">
                <a class="nav-link" id="denuncias-tab" href="{{url('/admin')}}" aria-controls="denuncias" aria-selected="false">Denúncias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="hist_bans-tab" href="{{url('/admin/history')}}" aria-controls="hist_bans" aria-selected="false">Histórico Bans</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="add_admin-tab" href="{{url('/admin/add')}}" aria-controls="add_admin" aria-selected="true">Adicionar Admin</a>
            </li>
            <li class="nav-item ml-md-auto">
                <a class="nav-link" id="search_user-tab" href="{{url('/admin/search')}}" aria-controls="search_user" aria-selected="false">
                    <i class="fa fa-search"></i> Pesquisar</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- DENUNCIAS CONTENT -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
            <div class="tab-pane fade " id="denuncias" role="tabpanel" aria-labelledby="denuncias-tab">
            </div>
            <div class="tab-pane fade" id="hist_bans" role="tabpanel" aria-labelledby="hist_bans-tab">  
            </div>
            <div class="tab-pane fade show active" id="add_admin" role="tabpanel" aria-labelledby="add_admin-tab">
                <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- ADD ADMIN -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
                <h3 class="text-center">Adicionar Admin</h3>
                <hr>
                <br>
                <form class="text-center" method="POST" action="{{ url('/admin/add') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Nome Completo
                            <input type="username" name="name" class="form-control" placeholder="E.g. Josefino Euralápio" value="{{ old('name') }}"
                            pattern="[A-Za-z0-9\-\.\_ ]*" maxlength="255" required autofocus></label>
                        @if ($errors->has('name'))
                            <small class="form-text error">
                                {{ $errors->first('name') }}
                            </small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Username<input type="username" name="username" class="form-control"  autocomplete="username" placeholder="Adminxxxx" value="{{ old('username') }}"
                        pattern="[A-Za-z0-9\-\_]*" maxlength="255" required></label>
                        @if ($errors->has('username'))
                            <small class="form-text error">
                                {{ $errors->first('username') }}
                            </small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Email address <input type="email" name="email" class="form-control" autocomplete="email" value="{{ old('email') }}" placeholder="name@example.com" maxlength="255" required></label>
                        @if ($errors->has('email'))
                            <small class="form-text error">
                                {{ $errors->first('email') }}
                            </small>
                        @endif
                    </div> 
                    <div class="form-group">
                        <label>Password <input type="password" name="password" class="form-control"  autocomplete="new-password" placeholder="Password"
                        pattern="[A-Za-z0-9\-\_]*" minlength="8" maxlength="255" required></label>
                        @if ($errors->has('password'))
                            <small class="form-text error">
                                {{ $errors->first('password') }}
                            </small>
                        @endif
                        <small class="form-text text-muted mb-4">
                            Pelo menos 8 carateres com letras e digitos
                        </small>
                    </div>
                    <div class="form-group">
                        <label>Confirmar Password<input type="password" name="password_confirmation" class="form-control" autocomplete="new-password" placeholder="Confirm Password"
                        pattern="[A-Za-z0-9\-\_]*" minlength="8" maxlength="255" required></label>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 40%; margin-top: 5px;">Registar</button>
                </form>
            </div>
            
            <div class="tab-pane fade" id="search_user" role="tabpanel" aria-labelledby="search_user-tab">
            </div>
        </div>
    </div>
@stop
