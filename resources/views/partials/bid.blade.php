<article class="bid" data-id="{{ $bidding->id }}">
    <li class="media">
        <div class="media-body col-lg-10 col-md-10">
                <span class=" col-lg-5 col-md-5 col-sm-8 text-muted">
                    <small class=" text-muted ">{{ $bidding->bidding_date }}</small>
                </span>
                <span class=" col-lg-2 col-md-2 col-sm-4">{{ $bidding->value_bid }} Eur</span>            
        </div>
         <span class=" col-lg-2 col-md-2 col-sm-3 pull-right">{{$bidding->bidguy }}</span>
    </li>

</article>