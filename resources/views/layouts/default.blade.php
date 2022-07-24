<!doctype html>
    <html>
        <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <head>
       @include('includes.head')
    </head>
    <body>
        @include('includes.header')
    <div class="container">
       <header class="row">

       </header>
       <div id="main" class="row">
               @yield('content')
       </div>
       <footer class="row">
           @include('includes.footer')
           @yield('scripts')
       </footer>
    </div>
    </body>
    </html>
