<?php

?>
        <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders Management</title>

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}">
</head>
<body>

@include('layouts.partials.nav')

<div class="container">
    @include('flash::message')

    @yield('content')
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script>$('#flash-overlay-modal').modal()</script>

</body>
</html>
