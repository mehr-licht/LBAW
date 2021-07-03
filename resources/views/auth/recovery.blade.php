@extends('layouts.appNoNav')

@section('css')
    <link rel="stylesheet" href="{{url('/css/register.css')}}">
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
@stop
    
<div id="bodyIntro" class="container-fluid text-center">
        <div class="row">
           
        <div class="col-md-4">
            </div>

            <div class="col-md-4">
                <form class="form-signin">
                    <img src="logotipo.png" class="mb-4 img-fluid mx-auto" alt="eBaw logo">
                    <h1 id="textRecuperarPalavraPasse" class="h3 mb-3 font-weight-normal">Insira email para confirmação</h1>
                    <label for="inputEmail" class="sr-only">Email</label>
                    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">

                    <p></p>
                    <br>
                    <br>
                </form>
            </div>
            <img src="Banner_01.png" class="img-fluid" alt="eBaw banner">
        </div>
    </div>
    
