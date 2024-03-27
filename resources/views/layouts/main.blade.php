<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="il3">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}">

    <title>il3</title>

    <link rel="canonical" href="{{ !empty(Route::current()) ? Route::current() ->uri : '' }}">
    {{--<link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dataTables/datatables.net-dt/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dataTables/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @viteReactRefresh
    @vite('resources/js/app.jsx')
    @stack('css')
    <script>
        var gVariables = {
            www: '{{ route('home') }}',
            @if (Auth::check())
                permisos: {!! json_encode(array_keys(Auth::user() ->permisos )) !!}
	    @else
		nopermisos: []
            @endif
        };
    </script>
  </head>

  <body>
    <nav class="navbar navbar-expand-md">{{-- fixed-top --}}
        <a href='{{ route('home') }}'>
            <img src="{{ asset('img/logo.jpg') }}">
        </a>
    </nav>
    <div style="width: 20%; display:inline-block; border:1px solid black; float:left; padding: 10px;">
        @include('layouts.menu')
    </div>
    <div style="width: 75%; display:inline-block; border:1px solid black; float:right; padding: 10px;">
        <main role="main" class="fluid-container">
            @include('layouts.flash')
            @yield('contenido')
        </main>
    </div>
    <div id='app'></div>
    {{--@include('layouts.modal')
    @include('layouts.confirm')
    @include('layouts.alert')
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/dataTables/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/dataTables/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>--}}
    <script  src="{{ asset('js/main.js') }}"></script>
    @stack('js')
  </body>
</html>
