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
    <script src="js/app.js"></script>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>
<body>
<div class="row">
    <div class="col offset-s3 s6" align="center">
        <h4>@yield('title')</h4>
    </div>
</div>
@yield('content')
</body>