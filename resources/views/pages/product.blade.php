@extends('layouts.app')

@section('title')
@parent
&middot; Product
@stop

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@stop

@section('css')
<link rel="stylesheet" href="{{url('/css/products.css')}}">
<link rel="stylesheet" href="{{url('/css/global.css')}}">
<link rel="stylesheet" href="{{url('/css/print.css')}}">
<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans');
</style>
@stop


@section('scripts')
<script src="{{ URL::asset ('/js/api.js') }}" defer></script>
<script src="{{ URL::asset ('/js/global.js') }}" defer></script>
@stop

@section('content')

<?php if (!empty($auction)) {$isauction = true;}else{$isauction = false;} ?>


<div class="container" id="outer" auction="{{$isauction}}">
    <?php if ($product->state_product === 'active' || $product->state_product === 'inactive' || $product->state_product === 'bought')
{ ?>
    </br>
    <div class="row">
        <div class="product-title col-8">
            <h1>{{ $product->name_product }}</h1>
        </div>
        </p>
        <div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="reportModelLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reportModelLabel">Denúncia:</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{ url('/products/report/' . $product->id) }}">
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
                                        placeholder="título da denúncia" required pattern="[a-zA-Z0-9\-\.\_]*"
                                        maxlength="50">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-2">
                                    <textarea class="form-control" name="textReport" placeholder="Explicitar Denúncia."
                                        required pattern="[a-zA-Z0-9\-\.\_]*" maxlength="500"></textarea>
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
    <div class="row">
        <div class="col-md-4">
            <div class="imgAbt">
                <a href="#">
                    <img src="{{$product->photo}}" class="img-thumbnail" alt="{{$product->name}}" id="image"
                        onClick="swipe();"></a>
            </div>
        </div>
        <div class="col-md-8">

            <?php if ((!$isauction && $product->state_product === 'active') || $product->state_product === 'bought')
    { ?>
            <div class="row" id="buyorpay">
                @if(isset($buyitnow) && $buyitnow->date_end > date_default_timezone_get())
                    @can('buy', $product)
                    <div class="btn-group cart col-md-4">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalBuy">
                            Comprar Agora!
                        </button>
                    </div>
                    @endcan
                    <div class="product-price col-md-2">Preço: <br><span class="font-propria-bold">
                            {{$buyitnow->final_value}}
                            Eur </span>
                    </div>
                @endif


                @can('pay', $product)
                <div class="btn-group cart col-md-3">
                    <button type="button" class="btn btn-primary" id="paybutton">
                        Pagar!</button>
                </div>
                @endcan

            </div>


            <?php 

    }
    else
    {
        if ($product->state_product == 'active')
        { ?>
            <?php if (!empty($biddings->first()))
            { ?>

            <div class="product-price">Leilão acaba dentro de <span class="font-propria-bold end-date">
                    {{$auction->date_end_auction}}</span></div>
            <hr>
            <div class="product-price">Licitação atual: <span
                    class="font-propria-bold"><?php echo($biddings->last()->value_bid? : $auction->bidding_base) ?></span>
            </div>
            </br>
            <?php
            } else { ?>  <div class="product-price">Leilão acaba dentro de <span class="font-propria-bold end-date">
                    {{$auction->date_end_auction}}</span></div>
            <hr>
            <div class="product-price">Sem licitações<span>
                    </span>
            </div>
            </br> <?php }
        }
        elseif ($product->state_product !== 'bought')
        { ?> <h1>Terminado</h1>

            <?php if ($isauction && !empty($biddings->first()))
            { ?>
            <div class="product-price">Licitação vencedora: <span
                    class="font-propria-bold">{{$biddings->last()->value_bid}} Eur</span></div>
            <?php
            }
            else
            { ?>
            <div class="product-price"> <span class="font-propria-bold">Sem licitações</span></div>
            <?php
            } ?>

            </br> <?php
        }
        if ($product->state_product == 'active')
        { ?>

            <div class="row">


                <div class="input-group mb-3 col-lg-5">
                    <div class="input-group-prepend">
                        <span class="input-group-text ">Sua licitação</span>
                    </div>
                    <?php if (!empty($biddings->first()))
            { ?>
                    <?php $placeholder = $biddings->last()->value_bid+ 10; ?>
                    <?php
            }
            else
            {
                $placeholder = $auction->bidding_base +10;
            } ?>
                    <input type="text" name="bidValue" id="bidValue" class="form-control col-lg-5"
                        placeholder={{$placeholder}} aria-label="Amount">
                    <div class="input-group-append">
                        <span class="input-group-text">EUR</span>
                    </div>
                </div>


                @can('bid', $product)
                <div class="btn-group cart col-lg-3">
                    <button onclick="getBid()" type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#modalBid">
                        Licitar!
                    </button>

                </div>
                @endcan

            </div>
            <?php if ($isauction)
    { ?>

            <div class="product-desc">
                </br>


                <div class="row">
                    <ul class="media-list">
                        <ul>

                            @if(!empty($biddings->first()))
                                @foreach ($biddings as $bidding)

                                    <section id="bids">
                                        @include('partials.bid')

                                    </section>

                                @endforeach
                            @endif
                            <article class="bid" id="bid-article"></article>
                        </ul>

                    </ul>
                </div>

            </div>
            <?php
    } ?>
            <br>
            <?php
        }
    }  ?>

            <hr>
            @if(!empty($editing))
                <form action="{{ url('products/' . $product->id . '/edit/') }}" method="post"  role="form" enctype="multipart/form-data">
                    {{ csrf_field() }} 
                    @method('PUT')
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        @if($product->is_new)
                        <label class="btn btn-outline-primary">
                            <input type="radio" name="isNew" id="usado_edit" autocomplete="off" value="false"> Usado
                        </label>
                        <label class="btn btn-outline-primary active">
                            <input type="radio" name="isNew" id="novo_edit" autocomplete="off" value="true"> Novo
                        </label>
                        @else
                        <label class="btn btn-outline-primary active">
                            <input type="radio" name="isNew" id="usado_edit" autocomplete="off" value="false"> Usado
                        </label>
                        <label class="btn btn-outline-primary">
                            <input type="radio" name="isNew" id="novo_edit" autocomplete="off" value="true"> Novo
                        </label>
                        @endif
                        <span class="text-danger"> {{ $errors->first('isNew') }}</span>
                    </div>

                    <hr>
                    <div>Vendedor: <a href="{{route('showuser', ['id' => $user->id])}}"
                            style="color:var(--main-font-color);"><span
                                class="font-propria-bold">{{ $user->username }}</span></a></div>
                    <div id="votes">
                        Votos: <span class="font-propria-bold">{{ $user->total_votes >0 ? $user->total_votes : 0}}</span>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>
                            Escolher Nova Imagem
                        </label>
                        <div class="input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-primary btn-file" style="border-top-right-radius:0;border-bottom-right-radius:0;">
                                    Procurar… 
                                    <input type="file" id="imgInp"  class="form-control" onchange="previewFile();" height="200" width="auto" name="photo" />
                                
                                </span>
                                <div class="row">
                                    <div class="col-sm-10">
                        
                                        <img id="imagePreview" src="{{ asset('sample.png') }}" height="200" width="auto" alt="Image preview">
                                        <span class="text-danger"> {{ $errors->first('photo') }}</span>
                                    </div>
                                </div>    
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="DescricaoProduto">Descrição do Produto *</label>
                        <div class="row">
                            <div class="col-sm-10">
                                <textarea id="DescricaoProduto" class="form-control" aria-label="DescricaoProduto"
                                required name="description">{{ $product->description }}</textarea>
                                <span class="text-danger"> {{ $errors->first('description') }}</span>
                            </div>
                        </div>
                    </div>

                    <hr>
                    @if(Auth::guard('admin')->check())
                    <button id="remove-product" product="{{ $product->id }}" remover="Auth::guard('admin')->getUser()"
                        type="button" class="btn btn-danger pull-left">Remover</button>
                    <div class="clearfix"></div>
                    @endif
                    @can('edit',$product)
                        <button id="edit-product" product="{{ $product->id }}" type="submit"
                            class="btn btn-primary pull-left">Confirmar</button>
                        <button id="cancel-product" product="{{ $product->id }}" type="button"
                            class="btn btn-secondary pull-left ml-3" onclick="history.back()">Cancelar</button>
                        <div class="clearfix"></div>
                    @endcan
                </form>
            @else
                <div>Estado: 
                    <span class="font-propria-bold"><?php echo ($product->is_new == true) ? "Novo" : "Usado" ?></span>
                </div>

                <hr>
                <div>Vendedor: <a href="{{route('showuser', ['id' => $user->id])}}"
                        style="color:var(--main-font-color);"><span
                            class="font-propria-bold">{{ $user->username }}</span></a></div>
                <div id="votes">
                    Votos: <span class="font-propria-bold">{{ $user->total_votes >0 ? $user->total_votes : 0}}</span>
                </div>

                <hr>

                <br>
                {{ $product->description }}

                <hr>
                @if(Auth::guard('admin')->check())
                <button id="remove-product" product="{{ $product->id }}" remover="Auth::guard('admin')->getUser()"
                    type="button" class="btn btn-danger pull-left">Remover</button>
                <div class="clearfix"></div>
                @endif
                @can('edit',$product)
                <a href="/products/{{$product->id}}/edit/">
                    <button id="edit-product" product="{{ $product->id }}" type="button"
                        class="btn btn-primary pull-left">Editar</button></a>
                <button id="cancel-product" product="{{ $product->id }}" type="button"
                    class="btn btn-secondary pull-left ml-3">Cancelar</button>
                <div class="clearfix"></div>
            <hr>
                @endcan
            @endif

            <p class=" product-title col-12 pr-0" align="right">
                @can('report',$product)
                <button type="button" class="btn " data-toggle="modal" data-target="#report">
                    <i class="fa fa-ellipsis-h" style="opacity:0.7;"></i>
                </button>
                @endcan
            </p>



            <?php if (!$isauction)
    { ?>
            <!-- Modal -->
            <div class="modal fade" id="modalBuy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">eBaw - confirmação</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Confirma que quer comprar este produto por {{$buyitnow->final_value}}
                                Eur?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                            <button id="addBuy" product="{{ $product->id }}" buyer="{{Auth::id()}}"
                                seller="{{ $product->id_owner }}" value={{$buyitnow->final_value}} type="button"
                                class="btn btn-info">Sim</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
    }
    else
    { ?>
            <!-- Modal -->
            <div class="modal fade" id="modalBid" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalBid">eBaw - confirmação</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <p>Confirma que quer fazer uma licitação de <span id="biddingValue"></span> Eur?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                            <button id="addBid" product="{{ $product->id }}" bidder="{{Auth::id()}}" value=getBid()
                                type="button" class="btn btn-info">Sim</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
    } ?>
        @if(empty($editing))
            <div id="commentSection" class="row bootstrap snippets">
                <div class="col-md-10 col-md-offset-2 col-sm-12">
                    <div class="comment-wrapper">
                        <div class="panel panel-info">
                            <div class="panel-body">
                                @if (Auth::guard('admin')->check() or Auth::check())
                                @auth
                                <?php $commenter = Auth::id() ?>
                                @else
                                <?php $commenter = - Auth::guard('admin')->Id(); ?>
                                @endauth
                                <textarea id="commentText" class="form-control" placeholder="Escreva um comentário..."
                                    rows="3"></textarea>
                                <br>
                                <button id="addComment" product="{{ $product->id }}" commenter="{{$commenter}}"
                                    liker={{Auth::id()}} type="button" class="btn btn-info pull-right">Submeter</button>
                                <div class="clearfix"></div>

                                <hr>
                                @endif
                                <ul class="media-list">
                                    <ul>
                                        @foreach ($comments as $comment)

                                        <section id="comments">
                                            @include('partials.comment')

                                        </section>

                                        @endforeach
                                        <article class="comment" id="comment-article"></article>
                                    </ul>

                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif

        </div>
        <?php
}
else
{
    echo "Produto Inexistente";
} ?>
    </div>
</div>
@stop