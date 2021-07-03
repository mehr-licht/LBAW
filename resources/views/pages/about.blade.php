@extends('layouts.app')

@section('title')
@parent
&middot; About
@stop

@section('css')
    <link rel="stylesheet" href="{{url('/css/global.css')}}">
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
    <link rel="stylesheet" href="{{url('/css/faq.css')}}">
@stop

@section('title')
    About
@stop


@section('content')


    <div class="row">

        <div class="col-1">
        </div>

        <div class="col-10">
            <!---- 2 Collapble 2 Beggin-->
            <!-- faq and things-->

            <div class="row p-3">
                <div class="col-sm-4 col-lg-3">
                    <img src="logotipo_inverse.png" alt="EBAW" style="width:80%">
                </div>
                <div class="col-sm-4 col-lg-3">
                    <img src="https://paginas.fe.up.pt/~ee10099/tese/lib/exe/fetch.php?cache=&w=900&h=296&tok=29e8a9&media=team:feup-logo.png" alt="FEUP" style="width:100%">
                </div>
                <div class="col-sm-4 col-lg-3">
                    <img src="https://web.fe.up.pt/~epia2017/wp-content/uploads/2017/07/DEI-transp2.png" alt="DEI" style="width:100%">
                </div>
                </div>
           <div class="page-header">
                <h1>About us </h1>
                <h1><small>EBAW, who are we?</small></h1>
                <hr>
                <p>
                <strong>
                    Ebaw is an online shop of generic products. <br>
                    Ebaw is a platform where users with intentions of selling products can post a description and a price. <br>
                    Users can be buyers and/or sellers.<br>
                    The selling can be in the form of auctions or with a fixed price. The first type of commercial transaction is called auction and the second one buyItNow. <br>
                </strong> <hr>
                    "Ebaw" seeks to satisfy people's desire to buy used products, cheaper than new ones, or just to satisfy the desire to collect. Another aim is to increase the reusability of goods and decrease the waste of resources.
                    We think that, in terms of the market, a project with these characteristics is relevant and differentiated in Portugal. They differ from those that currently exist, in a way that they are either very specific or are essentially based on sales of brand new products.
                    It will be a platform in a web environment, operating in real-time, aimed at the general public, exploiting to the maximum the best between adaptive and responsive design, in order to also take advantage of impulsive purchases, whether during the commute to school and/or work, lunch break, or other daily activity where there is a dead period of time.
                </p> <hr>
                <h2 class="code-line" data-line-start=0 data-line-end=1 ><a id="Our_Team_0"></a>Our Team</h2>
                <ul>
                    <li class="has-line-data" data-line-start="2" data-line-end="3">Luis Ricardo Marques Oliveira, <a href="mailto:up201607946@fe.up.pt">up201607946@fe.up.pt</a></li>
                    <li class="has-line-data" data-line-start="3" data-line-end="4">Henrique Miguel Bastos Gonçalves, <a href="mailto:up201608320@fe.up.pt">up201608320@fe.up.pt</a></li>
                    <li class="has-line-data" data-line-start="4" data-line-end="5">João Ruano Neto Veiga de Macedo, <a href="mailto:up201704464@fe.up.pt">up201704464@fe.up.pt</a></li>
                    <li class="has-line-data" data-line-start="5" data-line-end="6">Ricardo Manuel Gonçalves da Silva, <a href="mailto:up201607780@fe.up.pt">up201607780@fe.up.pt</a></li>
                </ul>
                <p class="has-line-data" data-line-start="8" data-line-end="9">We are a group of four students enrolled as of 2019/2020 in FEUP's Integrated Master's degree in Informatics and Computing Engineering. <br>
                    "EBAW" was developed in the context of the course "LBAW" <strong>Database and Web Applications Laboratory</strong>.<br>
                    In this course, the students should learn how to design and develop web-based information systems backed by database management systems, and this project aims to act as confirmation that these objectives were achieved.</p>
                <hr>

            </div>
        </div>


        <div class="col-1">
        </div>


    </div>
    <!---- tejo 2 Collapble 2 End-->


    <img src="Banner_01.png" class="img-fluid" alt="eBaw banner">

@stop

  
