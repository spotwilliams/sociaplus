<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Ricardo Vergara">
    <title>Premier League Simulator - Laravel</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    @stack('css')

    <!-- Custom styles for this template -->
    <link href="{{asset('css/site.css')}}" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="/">Home</a>

</nav>

<main role="main" class="container-fluid">

    <div class="site-container">
        @yield('content')

    </div>

</main><!-- /.container -->


<script src="{{ asset('js/jquery.slim.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>
