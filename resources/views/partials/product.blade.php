<div class="row">
    <div class="col-lg-3 col-sm-5 prod_th container-fluid ">
        <a href="/products/{{ $product->id }}">
            <img src="{{ $product->photo }}" width="100%" height="auto" alt="{{ $product->name_product }}">
        </a>
    </div>

    <div class="col-lg-9 text-left product">
        <a href="/products/{{ $product->id }}">
            <h3 class="product-title product">
                {{ $product->name_product }}
            </h3>
            <br>
        </a>
        <div class="row container">
            <div class="column col-lg-5">

                <div>
                    <h6>Estado:
                        <span class="font-propria-bold">{{ $product->state_product }}</span></h6>
                </div>
                <div>
                    <h6 class="product-price product">
                        @if( isset($product->auction->final_value) )    
                            <span class="font-propria-bold">EUR
                                {{ $product->auction->final_value }}
                            </span>
                        @else
                            <span class="font-propria-bold">EUR
                                {{  $product->buyitnow->final_value }}
                            </span>
                        @endif 
                    </h6>
                </div>
                <div>
                @if($product->id_owner == Auth::Id())
                    @if( isset($product->auction->final_value) )
                    <button type="button " class="btn btn-primary add_14 "
                        onclick="goToLicitationPage({{ $product->id }} );" data-toggle="modal "
                        data-target="#exampleModal ">
                        Ver! </button>
                    @else
                    <button type="button " class="btn btn-primary add_14 " data-toggle="modal"
                        onclick="goToBuyPage({{ $product->id }});" data-target="#exampleModal ">
                        Ver! </button>
                    @endif
                @else
                    @if( isset($product->auction->final_value) )
                    <button type="button " class="btn btn-primary add_14 "
                        onclick="goToLicitationPage({{ $product->id }} );" data-toggle="modal "
                        data-target="#exampleModal ">
                        Licitar! </button>
                    @else
                    <button type="button " class="btn btn-primary add_14 " data-toggle="modal"
                        onclick="goToBuyPage({{ $product->id }});" data-target="#exampleModal ">
                        Comprar! </button>
                    @endif
                @endif
                </div>
            </div>

            <div class="column col-lg-2"></div>

            <div class="column col-lg-5">
                <div>
                    <span></span>
                </div>
                <div>
                    @if (Auth::check())
                        <a href="/user/{{ $product->user->id }}">{{ $product->user->username }} <span>Votes:{{ $product->user->total_votes }}</span></a>    
                    @else
                         <a href="{{ url('/login') }}">{{ $product->user->username }}</a>
                    @endif
                    
                </div>
                <div>
                    <h5>

                        @if( isset($product->auction->final_value) )
                        <span id="dateCountDown" class="font-propria-bold">
                            {{$product->auction->date_end_auction}}
                        </span>
                        @else
                        <span id="dateCountDown" class="font-propria-bold">
                            {{$product->buyitnow->date_end}}
                        </span>
                        @endif

                        
                    </h5>
                </div>
            </div>

        </div>
    </div>

</div>
<hr>
<br>
