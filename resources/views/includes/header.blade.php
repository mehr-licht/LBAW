<!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- NAV BEGIN -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
<div>
    <!-- class="fixed-top"-->
    <div role="navigation" class="shadow-lg bg-white rounded">
        <nav id="barra_menu" class="navbar navbar-expand-md ">
            <a class="navbar-brand" href="/"  data-toggle="tooltip" title="Ir Para Página Inicial">
                <img class="img" src="/Logo_ebaw_Horizontal.png" style="width:200px;height: auto;" alt="eBaw logo">
            </a>
            <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- BEGIN SEARCH BAR -/-/-/-/-/-/-/-/-/-/-/-/-/- -->
            <div id="search" style="width:100%; min-width: 33%;">
                <form class="form-inline">
                    <div id="search_bar_container" class="container">
                        <div class="row justify-content-center">

                            <div class="col-3 col-lg-2 px-0">
                                <!--Categorias-->

                                <select class="custom-select" name="categories" id="searchCategoryDropDown"  data-toggle="tooltip" data-placement="bottom" title="Seleccionar Categoria">

                                    <option selected value="all"  data-toggle="tooltip" data-placement="bottom" title="Pesquisa Sem Filtro">Tudo</option>
                                    <option value="antiques"  data-toggle="tooltip" data-placement="bottom" title="Antiguidades">Antiguidades</option>
                                    <option value="art"  data-toggle="tooltip" data-placement="bottom" title="Artes">Artes</option>
                                    <option value="crafts" data-toggle="tooltip" data-placement="bottom" title="Artesanato">Artesanato</option>
                                    <option value="baby" data-toggle="tooltip" data-placement="bottom" title="Bebés">Bebés</option>
                                    <option value="healthBeauty" data-toggle="tooltip" data-placement="bottom" title="Beleza">Beleza</option>
                                    <option value="toys"  data-toggle="tooltip" data-placement="bottom" title="Brinquedos">Brinquedos</option>
                                    <option value="cars"  data-toggle="tooltip" data-placement="bottom" title="Carros">Carros</option>    
                                    <option value="pottery"  data-toggle="tooltip" data-placement="bottom" title="Cerâmica">Cerâmica</option>
                                    <option value="collecting"  data-toggle="tooltip" data-placement="bottom" title="Colecção">Colecção</option>
                                    <option value="comics"  data-toggle="tooltip" data-placement="bottom" title="Comics">Comics</option>
                                    <option value="computers" data-toggle="tooltip" data-placement="bottom" title="Computadores">Computadores</option>
                                    <option value="sports"  data-toggle="tooltip" data-placement="bottom" title="Desporto">Desporto</option>
                                    <option value="electronics"  data-toggle="tooltip" data-placement="bottom" title="Electrodomésticos">Electrodomésticos</option>
                                    <option value="philately" data-toggle="tooltip" data-placement="bottom" title="Filatelia">Filatelia</option>
                                    <option value="movies"  data-toggle="tooltip" data-placement="bottom" title="Filmes">Filmes</option>
                                    <option value="photo"  data-toggle="tooltip" data-placement="bottom" title="Fotos">Fotos</option>
                                    <option value="stationary"  data-toggle="tooltip" data-placement="bottom" title="Imoveis">Imóveis</option>
                                    <option value="musicalInstruments" data-toggle="tooltip" data-placement="bottom" title="Instrumentos Musicais">Instrumentos Musicais</option>
                                    <option value="houseGarden"  data-toggle="tooltip" data-placement="bottom" title="Jardinagem">Jardinagem</option>
                                    <option value="coins"  data-toggle="tooltip" data-placement="bottom" title="Moedas">Moedas</option>
                                    <option value="music"  data-toggle="tooltip" data-placement="bottom" title="Músicas">Músicas</option>
                                    <option value="bargains"  data-toggle="tooltip" data-placement="bottom" title="Negócios">Negócios</option>
                                    <option value="memorabiliaPortugal" data-toggle="tooltip" data-placement="bottom" title="Recordações">Recordações</option>
                                    <option value="watches"  data-toggle="tooltip" data-placement="bottom" title="Relógios">Relógios</option>
                                    <option value="clothingAndAccessories" data-toggle="tooltip" data-placement="bottom" title="Roupas e Acessórios">Roupas e Acessórios</option>
                                    <option value="stamps" data-toggle="tooltip" data-placement="bottom" title="Selos">Selos</option>
                                    <option value="travel" data-toggle="tooltip" data-placement="bottom" title="Viagens">Viagens</option>
                                    <option value="videoGames"  data-toggle="tooltip" data-placement="bottom" title="VideoJogos">VideoJogos</option>
                                </select>
                            </div>

                            <div class="col-7 col-lg-9 px-0">

                                <input id="searchInputProduct" class="form-control" name="search" type="search"
                                    placeholder="Pesquisa" aria-label="SearchProducts" style="width:100%; height:100%;"  data-toggle="tooltip" data-placement="bottom" title="Inserir Termos Para Pesquisar">

                            </div>

                            <div class="col-2 col-lg-1 px-0" data-toggle="tooltip" data-placement="top" title="Procurar Produto">
                                <button class="btn btn-info" type="button" style="height:100%; width:100%;"
                                    id="searchButton" data-toggle="tooltip" data-placement="bottom" title="Clickar Para Pesquisar">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- END SEARCH BAR -/-/-/-/-/-/-/-/-/-/-/- -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        @if (Auth::guard('admin')->check())

                        @elseif (Auth::check())
                        <a class="nav-link" href="{{ url('/products/create') }}"  data-toggle="tooltip" title="Colocar Produto À Venda">Vender</a>
                        @else
                        <a class="nav-link" href="{{ url('/login') }}" data-toggle="tooltip" title="Colocar Produto À Venda">Vender</a>
                        @endif
                    </li>

                    <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Notificações">

                        @if (Auth::check())
                        <a id="navbar_register_bt" class="nav-link" href="#modalNotifications" data-toggle="modal">
                            @else
                            <a class="nav-link" href="{{ url('/login') }}"  data-toggle="tooltip" title="Entrar">
                                @endif

                                <div class="d-none d-md-inline-flex">
                                    <!-- Button trigger modal -->
                                    <img src="/si-glyph-bellw.svg" style="width:20px;" alt="notification" data-toggle="tooltip" title="Notificações">
                                    <span id="h-nr-notifications1" class="badge badge-danger"
                                        style="font-size: 10px; top: -10px; position: relative; padding: .55em .5em;">
                                        0
                                    </span>
                                </div>

                            </a>
                    </li>



                    <li class="nav-item dropdown">
                        <div id="Conta" style="color:white;">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                style="display: inline-flex;">
                                <img src="/si-glyph-person-2w.svg" style="width:20px;" alt="user"  data-toggle="tooltip" title="Área do Usuário">
                                <span id="h-user" class="h-user d-block d-md-none d-lg-block" style="margin-left:.3em;"   data-toggle="tooltip" title="Área do Usuário">

                                    @if (Auth::check())
                                    {{ Auth::getUser()->name }}
                                    @elseif (Auth::guard('admin')->check())
                                    {{ Auth::guard('admin')->getUser()->username }}
                                    @else
                                    User
                                    @endif

                                </span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                @if (Auth::guard('admin')->check())
                                <a class="dropdown-item" href="/admin">Admin</a>
                                <a class="dropdown-item" href="/admin/history">Ver Banidos</a>
                                <a class="dropdown-item" href="/admin/search">Procurar Users</a>
                                @elseif(Auth::check())
                                <a class="dropdown-item" href="/users/{{ Auth::getUser()->id }}/history">Histórico</a>
                                <a class="dropdown-item" href="/users/{{ Auth::getUser()->id }}">Ver perfil</a>
                                <a class="dropdown-item"
                                    href="/users/{{ Auth::getUser()->id }}/notifications"  data-toggle="tooltip" title="Notificações">Notificações</a>
                                @else
                                <a class="dropdown-item" href="{{ url('/login') }}" data-toggle="tooltip" title="Histórico">Histórico</a>
                                <a class="dropdown-item" href="{{ url('/login') }}" data-toggle="tooltip" title="Ver Perfil">Ver perfil</a>
                                <a class="dropdown-item" href="{{ url('/login') }}" data-toggle="tooltip" title="Notificações">Notificações</a>
                                @endif

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ url('/faq') }}" data-toggle="tooltip" title="FAQ">FAQ</a>
                                <a class="dropdown-item" href="{{ url('/contact') }}" data-toggle="tooltip" title="Help & Contact">Help & Contact</a>
                                <div class="dropdown-divider"></div>
                                @if (Auth::check() or Auth::guard('admin')->check())
                                <a class="dropdown-item" href="{{ url('/logout') }}">
                                    Terminar Sessão
                                </a>
                                @else
                                <a class="dropdown-item" href="{{ url('/login') }}"> 
                                    Iniciar Sessão
                                </a>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="top" title="Sair">
                    @if (Auth::check() or Auth::guard('admin')->check())
                        <a class="nav-link" href="{{ url('/logout') }}">
                            <img src="/si-glyph-sign-outw.svg" style="width:25px;" alt="logout">
                            @else
                            <a class="nav-link" href="{{ url('/login') }}"data-toggle="tooltip" title="Entrar"> <img src="/si-glyph-sign-inw.svg"
                                    alt="eBaw login" style="width:25px;">
                                @endauth
                            </a>
                    </li>
                </ul>
            </div>
        </nav>


    </div>
</div>

<!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- NAV END -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-  -->


<!-- Modal -->
<div class="modal fade" id="modalNotifications" tabindex="-1" role="dialog" aria-labelledby="modalNotifications"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Notificações</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                @if (Auth::check())
                <div id="notification-list-section" data-user-id={{ Auth::getUser()->id }}>

                </div>
                @else
                <ul>
                    <li id="notifications-list">
                        Subscreva para receber notificações
                    </li>
                </ul>
                    @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>