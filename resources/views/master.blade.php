<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="css/materialize.min.css">
    <!-- Jquery -->
    <script src="js/jquery-2.1.4.min.js"></script>
    <!-- Compiled and minified JavaScript -->
    <script src="js/materialize.min.js"></script>
    {{--App related--}}
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <div class="container">
        <div class="card-panel">
            @yield('content')
        </div>
    </div>
</body>
</html>