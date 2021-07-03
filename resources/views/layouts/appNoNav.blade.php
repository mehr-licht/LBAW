@section('title')
{{ config('app.name', 'Laravel') }}
@stop
<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  @include('includes.head')
</head>

<body id="bodyIntro">
  
  <main>
    <section id="content">
      @yield('content') 
    </section>

    <section id="footer">
      @include('includes.footer')
    </section>
    
  </main>
</body>

</html>
