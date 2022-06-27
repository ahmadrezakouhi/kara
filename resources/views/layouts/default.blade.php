<!doctype html>
    <html>
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
