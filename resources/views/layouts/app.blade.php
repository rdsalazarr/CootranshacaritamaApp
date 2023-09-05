<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="Cootranshacaritama" />
        <meta name="keywords" content="Cootranshacaritama, Cooperativas, Transporte,Carga, Encomiendas, Pasajeros" />
        <meta name="author" content="implesoft">
        <meta name="languaje" content="Espa&ntilde;ol"/>
        <meta name="robots" content="index, follow">
        <meta name="theme-color" content="#44ac34" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Cootranshacaritama APP'}}</title>
        <link rel="shortcut icon" href="{{asset('images/logo.png')}}" type="image/png"/>
        
      
        @viteReactRefresh
        @yield('variables')
        @yield('content')

    <body>
        <div id="app"></div>
        <div id='snake' ></div>
    </body>

    <script src="{{asset('tinymce/tinymce.min.js')}}"></script>
</html>