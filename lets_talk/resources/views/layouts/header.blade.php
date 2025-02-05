    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Let's Talk - @yield('title')</title>
    <link rel="icon" href="{{asset('img/logo.png')}}" type="image/x-icon">
    @yield('css')
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=PT+Sans:300,400,700,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lora:400,400italic,700,700italic' rel='stylesheet'
        type='text/css'>

    <!-- Styles -->
     {{-- <link rel="stylesheet" type="text/css" href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('font-awesome-4.5.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/404.css') }}">

    {{-- Styles Login Entrenador --}}
    {{-- <link rel="icon" type="image/png" href="{{asset('favicon.ico')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('iconic/css/material-design-iconic-font.min.css')}}"> --}}
     <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
	{{-- <link rel="stylesheet" type="text/css" href="{{asset('vendor/animate/animate.css')}}"> --}}
	<link rel="stylesheet" type="text/css" href="{{asset('vendor/css-hamburgers/hamburgers.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('vendor/animsition/css/animsition.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('vendor/select2/select2.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('vendor/daterangepicker/daterangepicker.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/main.css')}}">

    {{-- Sweetalert2 --}}
	<link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert2.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert2.min.css')}}">
    <!--  Js -->
    <script src="{{ asset('js/modernizr.custom.js') }}"></script>
    <script src="{{ asset('js/jquery-2.1.3.min.js') }}"></script>
