@section('title')
{{ config('app.name', 'Laravel') }}
@stop

<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  @include('includes.head')
</head>

<body>
  <main>
    <header id="header">
      @include('includes.header')
	  </header>

    <section id="content">
      @include('partials.flash-message')
      @yield('content')
    </section>

    <footer id="footer">
      @include('includes.footer')
    </footer>

  </main>
</body>

</html>
