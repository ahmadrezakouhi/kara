<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>کارا</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/bootstrap.rtl.min.css" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/dataTables.bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/fixedHeader.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/responsive.bootstrap.min.css"/>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/mdb.rtl.min.css"/>
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('includes.header')

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @include('includes.footer')
    @yield('scripts')
</body>
</html>
