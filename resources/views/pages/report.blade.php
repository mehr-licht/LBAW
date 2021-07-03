@extends('layouts.app')

@section('title')
@parent
&middot; Report Page
@stop

@section('css')
    <link rel="stylesheet" href="{{url('/css/global.css')}}">
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
    <link rel="stylesheet" href="{{url('/css/admin.css')}}">
@stop

@section('title')
    Report
@stop

@section('scripts')
<script src="{{ URL::asset ('/js/admin.js') }}" defer></script>
<script src="{{ URL::asset ('/js/global.js') }}" defer></script>
@stop

@section('content')
<div class="container mb-4 mt-4" id="DenunciaMainPage">
    <br/>
    <div class="d-sm-flex justify-content-between" id="DenPagHeaderTags">
        <h5>
        <span class="badge badge-info"><a href="#" style="color:white; text-decoration: none;"># {{$report[0]->id}}</a></span>
        </h5>
        
        @if($report[0]->state_report == 'assume')
        <button class="btn btn-success btn-sm" 
            type="button" 
            style="width: 5rem" 
            data-toggle="modal" 
            data-target="#AssumeModel">Assumir
        </button>
        @elseif($report[0]->state_report == 'assumed')
            @if(Auth::guard('admin')->Id() === $admin->id_admin)
            <button class="btn btn-secondary btn-sm" 
                    type="button" 
                    style="width: 5rem" 
                    data-toggle="modal" 
                    data-target="#AssumedModel">Assumido
            </button>  
            @else
            <button class="btn btn-secondary btn-sm" 
                    type="button" 
                    style="width: 5rem" 
                    data-toggle="modal" 
                    data-target="#">Assumido
            </button>  
            @endif
        @else
            <button class="btn btn-danger btn-sm" 
                    type="button" 
                    style="width: 5rem" 
                    data-toggle="modal" 
                    data-target="#">Resolvido
            </button>       
        @endif
    
    </div>
    <h3 class="mb-2 mt-2 text-center">{{$report[0]->reason}}</h3>
    <hr class="mt-2 mb-2" />

    <div class="d-flex justify-content-center">
        <small id="responsible_admin" data-id="{{$report[0]->id_admin}}" class="form-text text-muted" style="margin-top:0;">
        Admin Responsável:
        @isset($admin)
        <span id="admin_name"> {{$admin->name}}</span>
        @endisset
        </small>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div>
                <br>
                <h6><strong>Descrição da Denúncia:</strong></h6>
                {{$report[0]->text_report}}    
            </div>
            <br>
            <div class="d-flex justify-content-center">
                @if($typeOfReport == "user")
                <a href="/user/{{$id_conteudo}}">
                    <button type="button" class="btn btn-primary">
                        Ver Contexto
                    </button>
                </a>
                @else
                <a href="/products/{{$id_conteudo}}">
                    <button type="button" class="btn btn-primary">
                        Ver Contexto
                    </button>
                </a>
                @endif
            </div>
            <br>
            <div class="d-flex justify-content-between">
            <strong>Denunciante: <a href="/user/{{$report[0]->id_reporter}}" data-id="{{$report[0]->id_reporter}}">{{$reporter[0]->name}}</a></strong> <small>{{ $report[0]->date_report }}</small>
            </div>
            <hr>
            <div class="d-flex justify-content-between" id="DenOutcomeHeader">
                <h6><strong>Consequências</strong></h6>
                @isset($admin)
                    @if(Auth::guard('admin')->Id() === $admin->id_admin)
                        <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#EditConseqModel">
                            <i class="fa fa-edit"></i> Editar</button>
                    @endif
                @endisset
            </div>
            <br>
            <div class="d-flex" id="DenOutcomeBody">
            <p><a href="/user/{{$punished[0]->id}}">{{$punished[0]->name}} </a>:
            @isset($report[0]->consequence)
                @if($report[0]->consequence === 'suspend')
                Suspenso.
                @elseif($report[0]->consequence === 'ban')
                Banido por {{$report[0]->punishement_span}} Dias. Razão: {{$report[0]->observation_admin}}
                @else
                Nada. Razão: {{$report[0]->observation_admin}}
                @endif
            @endisset
            </p>
            </div>
            <hr style="margin-top:0;">
        </div>
    </div>
</div>
    <br>

    <!-- Modals -->
    <!-- Assume Modal -->
    <div class="modal fade" id="AssumeModel" tabindex="-1" role="dialog" aria-labelledby="AssumeModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AssumeModelLabel">Assumir Denúncia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    Tens a certeza que queres <strong>assumir</strong> esta denúncia?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form method="POST" action="{{ url('/report/' . $report[0]->id) }}"> 
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="reportId" value="{{ $report[0]->id }}">
                    <button id="assumeReportConfirmation" type="submit" class="btn btn-primary">Sim!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Assumido Modal -->
    <div class="modal fade" id="AssumedModel" tabindex="-1" role="dialog" aria-labelledby="AssumedModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AssumedModelLabel">Desistir da Denúncia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    Tens a certeza que queres <strong>deixar</strong> esta denúncia?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form method="POST" action="{{ url('/report/' . $report[0]->id) }}"> 
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="reportId" value="{{ $report[0]->id }}">
                    <button id="disassumeReportConfirmation" type="submit" class="btn btn-primary">Sim!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- EditConseqModel -->
    <div class="modal fade" id="EditConseqModel" tabindex="-1" role="dialog" aria-labelledby="EditConseqModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditConseqModelLabel">Consequência</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ url('/admin/bans/member/' . $report[0]->id_punished) }}"> 
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="reportId" value="{{ $report[0]->id }}">

                    <div class="modal-body text-left">
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
                            <label for="typeOfConsequenceSelect">Tipo</label>
                            <select class="form-control" id="typeOfConsequenceSelect" name="consequence">
                            <option value="suspend" selected>Suspender</option>
                            <option value="ban">Banir</option>
                            <option value="do_nothing">Nada</option>
                            </select>
                        </div>
                        <div class="form-group" id="punishement_spanForm" style="display:none;">
                            <label for="punishement_span">Dias de ban</label>
                            <input type="number" class="form-control" id="punishement_span" name="punishement_span" min="1">
                        </div>
                        <div class="form-group" id="banReasonTextForm" style="display:none;">
                            <label for="banReasonText">Razão</label>
                            <textarea class="form-control" id="banReasonText" name="observation_admin" rows="3" placeholder="Razões para consequência..." maxlength="1000"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button id="consequenceConfirmation" type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
