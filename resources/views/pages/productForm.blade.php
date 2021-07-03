@extends('layouts.app')

@section('title')
@parent
&middot; Create Product
@stop

@section('css')
    <link rel="stylesheet" href="{{url('/css/products.css')}}">
    <link rel="stylesheet" href="{{url('/css/global.css')}}">
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans');
    </style>
@stop


@section('js')
@parent
<script src="{{ URL::asset ('/js/createProductForm.js') }}" defer></script>  
@stop


@section('title')
    eBaw &middot; online shopping
@stop


@section('content')
    <div class="mx-auto" id="Criar_Prod_Main_Container" style="margin-top:30px;">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @endif

        <form action="{{ url('products/create') }}" method="post"  role="form" enctype="multipart/form-data">
            {{ csrf_field() }} 
            <br/>
                <h1 class="mb-4">Criar venda</h1>
                    <div class="form-group">
                        @csrf
                            <label for="titulo">Titulo *  
                            </label> 
                            <div class="row">
                                <div class="col-sm-8">
                                <input type="text" class="form-control" id="titulo" aria-describedby="tituloProduto" name="nameProduct" 
                                placeholder="Titulo do Produto" required data-toggle="tooltip" data-placement="left" title="Título do Produto">
                            </div>
                                <div class="col-sm-4">
                                    <h4>
                                        <i class="fa fa-question-circle form-info" aria-hidden="true" data-toggle="popover" data-placement="top" 
                                        data-content="Refira a marca, o modelo e as mais importantes características"></i>
                                    </h4>
                                </div>
                            
                                </div>
                              </div>
                            
                            <div class="container">
                        
                    </div>

                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-outline-primary active" data-toggle="tooltip" data-placement="left" title="Usado">
                        <input type="radio" name="isNew" id="usado" autocomplete="off" value="false"> Usado
                    </label>
                    <label class="btn btn-outline-primary" data-toggle="tooltip" data-placement="left" title="Novo">
                        <input type="radio" name="isNew" id="novo" autocomplete="off" value="true" > Novo
                    </label>
                <div class="container">
                    <h4>
                        <i class="fa fa-question-circle form-info" aria-hidden="true" data-toggle="popover" data-placement="top" 
                        data-content="É considerado um produto novo, todo o produto que vem embalado da fábrica. Se nãa for este o caso, escolha usado"></i>
                    </h4>
                </div>

                </div>
                <br/>
                <br/>

                <div class="form-group mb-3">
                    <label for="CategoriaSelect">Categoria *</label>
                    <div class="row">
                        <div class="col-sm-8">
            
                    <select class="custom-select" id="CategoriaSelect" style="border-radius: 0;" name="category" required>
                        <option selected data-toggle="tooltip" data-placement="left" title="Clique para escolher a categoria">Escolha...</option>

                        <option value="antiques"  data-toggle="tooltip" data-placement="left" title="Antiguidades">Antiguidades</option>
                        <option value="art"  data-toggle="tooltip" data-placement="left" title="Artes">Artes</option>
                        <option value="crafts" data-toggle="tooltip" data-placement="left" title="Artesanato">Artesanato</option>
                        <option value="baby" data-toggle="tooltip" data-placement="left" title="Bebés">Bebés</option>
                        <option value="healthBeauty" data-toggle="tooltip" data-placement="left" title="Beleza">Beleza</option>
                        <option value="toys"  data-toggle="tooltip" data-placement="left" title="Brinquedos">Brinquedos</option>
                        <option value="cars"  data-toggle="tooltip" data-placement="left" title="Carros">Carros</option>    
                        <option value="pottery"  data-toggle="tooltip" data-placement="left" title="Cerâmica">Cerâmica</option>
                        <option value="collecting"  data-toggle="tooltip" data-placement="left" title="Colecção">Colecção</option>
                        <option value="comics"  data-toggle="tooltip" data-placement="left" title="Comics">Comics</option>
                        <option value="computers" data-toggle="tooltip" data-placement="left" title="Computadores">Computadores</option>
                        <option value="sports"  data-toggle="tooltip" data-placement="left" title="Desporto">Desporto</option>
                        <option value="electronics"  data-toggle="tooltip" data-placement="left" title="Electrodomésticos">Electrodomésticos</option>
                        <option value="philately" data-toggle="tooltip" data-placement="left" title="Filatelia">Filatelia</option>
                        <option value="movies"  data-toggle="tooltip" data-placement="left" title="Filmes">Filmes</option>
                        <option value="photo"  data-toggle="tooltip" data-placement="left" title="Fotos">Fotos</option>
                        <option value="stationary"  data-toggle="tooltip" data-placement="left" title="Imoveis">Imóveis</option>
                        <option value="musicalInstruments" data-toggle="tooltip" data-placement="left" title="Instrumentos Musicais">Instrumentos Musicais</option>
                        <option value="houseGarden"  data-toggle="tooltip" data-placement="left" title="Jardinagem">Jardinagem</option>
                        <option value="coins"  data-toggle="tooltip" data-placement="left" title="Moedas">Moedas</option>
                        <option value="music"  data-toggle="tooltip" data-placement="left" title="Músicas">Músicas</option>
                        <option value="bargains"  data-toggle="tooltip" data-placement="left" title="Negócios">Negócios</option>
                        <option value="memorabiliaPortugal" data-toggle="tooltip" data-placement="left" title="Recordações">Recordações</option>
                        <option value="watches"  data-toggle="tooltip" data-placement="left" title="Relógios">Relógios</option>
                        <option value="clothingAndAccessories" data-toggle="tooltip" data-placement="left" title="Roupas e Acessórios">Roupas e Acessórios</option>
                        <option value="stamps" data-toggle="tooltip" data-placement="left" title="Selos">Selos</option>
                        <option value="travel" data-toggle="tooltip" data-placement="left" title="Viagens">Viagens</option>
                        <option value="videoGames"  data-toggle="tooltip" data-placement="left" title="VideoJogos">VideoJogos</option>
                    </select>

                </div>
                <div class="col-sm-4">
                    <h4>
                        <i class="fa fa-question-circle form-info" aria-hidden="true" data-toggle="popover" data-placement="top" 
                        data-content="Seleccione uma categoria, ou pelo menos a mais aproximada"></i>
                    </h4>
                </div>
            </div>


                </div>

                <div class="form-group">
                    <label>
                        Escolher Imagem *
                    </label>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <span class="btn btn-primary btn-file" style="background-color:rgb(0, 123, 255);color:white ;border-top-right-radius:0;border-bottom-right-radius:0;">
                                Procurar… 
                                <input type="file" id="imgInp"  class="form-control" onchange="previewFile();" height="200" width="auto" name="photo" data-toggle="tooltip" data-placement="left" title="Escolher foto"/>
                               
                            </span>
                            <div class="row">
                                <div class="col-sm-10">
                    
                            <img id="imagePreview" src="{{ asset('sample.png') }}" height="200" width="auto" alt="Image preview">
                              <span class="text-danger"> {{ $errors->first('photo') }}</span>
                            </div>
                            <div class="col-sm-2">
                                <h4>
                                    <i class="fa fa-question-circle form-info" aria-hidden="true" data-toggle="popover" data-placement="top" 
                                    data-content="Escolha uma foto do produto no seu sistemas de ficheiros. Extensões .png .gif. Até ao máximo de 2MB."></i>
                                </h4>
                            </div>
                        </div>    
                         </span>
                    </div>
                   
                </div>

                <div class="form-group">
                    <label for="DescricaoProduto">Descrição do Produto *</label>
                    <div class="row">
                        <div class="col-sm-10">              
                            <textarea id="DescricaoProduto" class="form-control" aria-label="DescricaoProduto" required name="description" maxlength="1000" data-toggle="tooltip" data-placement="left" title="Descrição do Produto"></textarea>
                        </div>
                        <div class="col-sm-2">
                            <h4>
                                <i class="fa fa-question-circle form-info" aria-hidden="true" data-toggle="popover" data-placement="top" 
                                data-content="Descreva as características, as condições e especificidades do seu produto"></i>
                            </h4>
                        </div>
                    </div>
                </div>

                <br/>

                <!--    VENDA DIRETA | LEILÃO   -->
                <div class="container" id="sale-auction-tabs" style="padding-left: 0;">
                    <ul class="nav nav-tabs" id="myTab" role="tablist" style="background-color: #f8f9fa;">
                        <li class="nav-item">
                            <a class="nav-link active" id="sale-tab" data-toggle="tab" href="#sale" role="tab" aria-controls="sale" aria-selected="true" data-toggle="tooltip" data-placement="left" title="Clickar par criar venda directa">Venda Direta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="auction-tab" data-toggle="tab" href="#auction" role="tab" aria-controls="auction" aria-selected="false" data-toggle="tooltip" data-placement="left" title="Clickar para criar leilão">Leilão</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="myTabContent">
                        <br/>
                        <div class="tab-pane fade" id="auction" role="tabpanel" aria-labelledby="auction-tab">
                            <div class="form-group">

                                <label for="LicitacaoBase" data-toggle="tooltip" data-placement="left" title="Licitação Base">Licitação Base (EUR) *</label>
                            
                                <div class="row">
                                    <div class="col-sm-10" >
                                        
                                        <input type="text" id="LicitacaoBase" class="form-control" 
                                                aria-label="Amount (to the nearest dollar)" 
                                                placeholder="Euros" 
                                                oninput="this.value=this.value.replace(/(?![0-9])./gmi,'')" name="biddingBase">
                            
                                    </div>
                                    <div class="col-sm-2">
                                        <h4>
                                            <i class="fa fa-question-circle form-info" aria-hidden="true" data-toggle="popover" data-placement="top" 
                                            data-content="Digite o preço inicial, pelo qual o leilão vai iniciar"></i>
                                        </h4>
                                    </div>
                                    </div>
                            
                            
                            
                            </div>
                            <div class="form-group">
                                <label for="DataFinal" data-toggle="tooltip" data-placement="left" title="Data Final">Data final *</label>
                                <div class="row">
                                    <div class="col-sm-10">
            
                                
                                <input type="date" id="DataFinal" class="form-control" name="dateEndAuction">
                            
                            
                                </div>
                                <div class="col-sm-2">
                                    <h4>
                                        <i class="fa fa-question-circle form-info" aria-hidden="true" data-toggle="popover" data-placement="top" 
                                        data-content="Indique a data em que o leilão terminará"></i>
                                    </h4>
                                </div>
                                </div>
                        
                            
                            
                            </div>
                            <div class="form-group">
                                <label for="HoraFinal" data-toggle="tooltip" data-placement="left" title="Hora Final">Hora final *</label>
                                <div class="row">
                                    <div class="col-sm-10">

                                    <input type="time" id="HoraFinal" class="form-control" name="hourEndAuction">
                                </div>
                                <div class="col-sm-2">
                                    <h4>
                                        <i class="fa fa-question-circle form-info" aria-hidden="true" data-toggle="popover" data-placement="top" 
                                        data-content="Indique a hora em que o leilão terminará"></i>
                                    </h4>
                                </div>
                                </div>
            
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">

                                <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Clickar para finalizar a criação do leilão">Criar Leilao</button>
                        </div>
                        <div class="col-sm-8">
                            <h4>
                                <i class="fa fa-question-circle form-info" aria-hidden="true" data-toggle="popover" data-placement="top" 
                                data-content="Ao pressionar o botão irá publicar a sua venda, poerá alterar alguns dados na área do utilizador"></i>
                            </h4>
                        </div>
                        </div>
                       
                        </div>
                
                
                        <div class="tab-pane fade show active" id="sale" role="tabpanel" aria-labelledby="sale-tab">
                            <div class="form-group">
                                <label for="price" data-toggle="tooltip" data-placement="left" title="Preço">Preço (EUR) *</label>
                                <div class="row">
                                    <div class="col-sm-8">
                
                                        <input type="text" id="price" class="form-control" aria-label="Amount (to the nearest dollar)" 
                                                placeholder="Euros" 
                                                oninput="this.value=this.value.replace(/(?!([0-9]))./gmi,'')" 
                                                name="finalValue">
                                    </div>
                                    <div class="col-sm-4">
                                        <h4>
                                            <i class="fa fa-question-circle form-info" aria-hidden="true" data-toggle="popover" data-placement="top" 
                                            data-content="Insira o preço final de venda, este preço poderá ser alterado na área do utilizador"></i>
                                        </h4>
                                    </div>
                                </div>
                            
                            </div>
                            <div class="form-group">
                                <label for="FinalDate" data-toggle="tooltip" data-placement="left" title="Data Final">Data final *</label>
                                <div class="row">
                                    <div class="col-sm-8">
                
                                <input type="date" id="FinalDate" class="form-control" name="dateEnd">
                            </div>
                            <div class="col-sm-4">
                                <h4>
                                    <i class="fa fa-question-circle form-info" aria-hidden="true" data-toggle="popover" data-placement="top" 
                                    data-content="Seleccione o data em que a venda terminará, no final a venda deixará de estar diponivel, mas poderá reeditar a venda"></i>
                                </h4>
                            </div>
                        </div>
                   
                
                
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                
                                    <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Clicar para finalizar a criação da venda">Criar Venda</button>
                                </div>
                                <div class="col-sm-8">
                                    <h4>
                                        <i class="fa fa-question-circle form-info" aria-hidden="true" data-toggle="popover" data-placement="top" 
                                        data-content="Após pressionar o botão o seu produto vai estar disponível ao público e pronto a receber comentários"></i>
                                    </h4>
                                </div>
                            </div>
                               
                        </div>
                    </div>
                    
                </div>
        </form>
    </div>

@stop
