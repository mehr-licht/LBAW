@extends('layouts.app')

@section('title')
@parent
&middot; Search Users
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
                <a class="nav-link" id="denuncias-tab"  href="{{url('/admin')}}" role="tab" aria-controls="denuncias" aria-selected="false">Denúncias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="hist_bans-tab" href="{{url('/admin/history')}}" role="tab" aria-controls="hist_bans" aria-selected="false">Histórico Bans</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="add_admin-tab" href="{{url('/admin/add')}}" role="tab" aria-controls="add_admin" aria-selected="false">Adicionar Admin</a>
            </li>
            <li class="nav-item ml-md-auto">
                <a class="nav-link active" id="search_user-tab" href="{{url('/admin/search')}}" role="tab" aria-controls="search_user" aria-selected="true">
                    <i class="fa fa-search"></i> Pesquisar</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- DENUNCIAS CONTENT -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
            <div class="tab-pane fade" id="denuncias" role="tabpanel" aria-labelledby="denuncias-tab">
            </div>
            <div class="tab-pane fade" id="hist_bans" role="tabpanel" aria-labelledby="hist_bans-tab">
                <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- BAN HIST CONTENT -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
            </div>
            <div class="tab-pane fade" id="add_admin" role="tabpanel" aria-labelledby="add_admin-tab">
                <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- ADD ADMIN -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
            </div>
            <div class="tab-pane fade show active" id="search_user" role="tabpanel" aria-labelledby="search_user-tab">
                <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- SEARCH USER -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
                <h3 class="text-center">Utilizadores</h3>
                <hr>
                <div id="search_users">
                    <form method="GET" action="{{ route('admin.search') }}" id="searchFormAdmin" class="form-inline my-2 justify-content-center">
                        @csrf
                        <input id="searchInputAdmin" class="form-control" name="search" type="search" placeholder="Procurar User" aria-label="Search">
                        <button class="btn btn-outline-info my-2 my-sm-0" type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <hr>
                <table class="table table-responsive-md table-bordered table-sm" id="search_user_table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="search_user_table_body" class="not_center">

                        @isset($users)
                            @each('partials.userSearch', $users, 'users')
                        @endisset
                    </tbody>

                </table>
                <br>
                <div class="row justify-content-around justify-content-md-center">
                    <nav aria-label="AdminPageNav" class="text-center">
                        <ul class="pagination text-center">
                            @isset($users)
                                @if(isset($search))
                                    {{ $users->appends(['search' => $search])->links() }}
                                @else
                                    {{ $users->links() }}
                                @endif
                            @endisset
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

@stop
