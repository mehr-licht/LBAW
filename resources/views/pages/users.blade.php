@extends('layouts.app')

@section('title')
@parent
&middot; User Profile
@stop

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@stop

@section('css')
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('/css/profile.css')}}">
    <link rel="stylesheet" href="{{url('/css/products.css')}}">
    <link rel="stylesheet" href="{{url('/css/global.css')}}">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans');
    </style>    
@stop


@section('scripts')
<script src="{{ URL::asset ('/js/api.js') }}" defer></script>
<script src="{{ URL::asset ('/js/global.js') }}" defer></script>
@stop

@section('content')

 
<div class="container-fluid" style="margin-top: 20px;">

    <!-- BEGIN profile-tabs-div -->
    <div class="container row">
        <div class="col-md-3 hidden-xs col-lg-3 d-block">
            <div class="wrappersidebar not_center">
                <!-- Sidebar -->
                <nav id="sidebar" class="vcenter">
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-9">
                            <hr>

                            <ul id="sidebar_components" class="list-unstyled components">

                                <li id="sidebar_perfil" class="active">
                                    <a class="nav-link2" onclick="showGeral()" href="/users/{{$user->id}}"
                                        data-toggle="collapse" data-toggle="collapse"
                                        aria-expanded="false">Perfil</a>
                                </li>
                                <li id="sidebar_notifications">
                                    <a class="nav-link2" onclick="showNotif()" href="/users/{{$user->id}}/notifications"
                                        data-toggle="collapse">Notificações</a>
                                </li>
                                <li id="sidebar_historico">
                                    <a class="nav-link2" onclick="showHist()" href="/users/{{$user->id}}/history"
                                        data-toggle="collapse" data-toggle="collapse"
                                        aria-expanded="false">Histórico</a>

                                </li>
                                <!-- <li>
                                    <a class="nav-link2" onclick="showEditar()" href="#" data-toggle="collapse">Editar Perfil</a>
                                </li>-->
                            </ul>
                            <hr>

                        </div>
                        <div class="col-lg-1"></div>
                    </div>
                </nav>

            </div>
        </div>

        <!-- MAIN SECTION -->

        <div class="col-lg-9 col-sm-9 d-block push" id="geral">
            <!-- BEGIN PERFIL geral -->
            <br>
            <br>
            <div class="row">
                <div class="col-lg-3 prod_th">
                    <img src="{{$user->photo ? $user->photo : asset('user.jpg')}}" width="160px" height="auto" alt="user photo">
                    <p></p>
                    @if(Auth::user()->id == $user->id)
                    <a href="/users/edit"><buttown class="btn btn-primary btn-sm"
                            type="button" style="width: 6rem"><i class="fa fa-edit"></i> Editar</button></a>
                    @endif
                </div>
                <div class="col-lg-3">
                    <br>
                    <h3 class="profile-name profile"> {{$user->name}}
                    </h3>
                    <div>
                        <a href="# ">{{$user->username}}</a>
                        <br>
                    </div>
                    <div>
                        <span><?php if ($user->total_votes > 0) echo "+";?>
                        {{$user->total_votes}}</span>
                    </div>
                    <div style="margin: 10px 0;">
                        <a href="#"><i class="fa fa-dribbble"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-linkedin"></i></a>
                        <a href="#"><i class="fa fa-facebook"></i></a>

                    </div>
                </div>

                <div class="col-lg-5 text-left profile">

                    <div class="container row">

                        <div class=" profile-descr profile ">
                            <br>
                            <p> {{$user->description}}
                            </p>
                        </div>
                        <div class="col-sm-1 col-xs-1 col-md-1"></div>
                    </div>

                </div>

            </div>

        </div>
        <!-- END PERFIL geral -->

    </div>
    <!-- END profile-tabs-div -->


    <!-- BEGIN BOTAO DENUNCIAR -->
    <div class="container row">
        <div class="col-10"></div>
        <div class="col-2" id="btn_denunciar">
            <div class="row">
                <p class="product-title">
                    <a class="denounce" data-toggle="modal" data-target="#report" id="btn_denunciar_in">
                        <strong>...</strong>
                    </a>
                </p>
                <div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="reportModelLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reportModelLabel">Denúncia:</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="{{ url('users/' . $user->id . '/report') }}"> 
                                @csrf
                                <div class="modal-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" id="title" name="reason"
                                                placeholder="título da denúncia" required pattern="[a-zA-Z0-9\-\.\_]*" maxlength="50">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-2">
                                            <textarea class="form-control" name="textReport" placeholder="Explicitar Denúncia." required
                                                pattern="[a-zA-Z0-9\-\.\_]*" maxlength="500"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button id="reportConfirmation" type="submit" class="btn btn-primary">Submeter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END BOTAO DENUNCIAR -->
    <br>
</div>
</div>

@stop