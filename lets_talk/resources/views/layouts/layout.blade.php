<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.header')
</head>
<body>
    <div class="contenido-cuerpo">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="logo-box">
                    <a href="{{route('home')}}">
                        <img src="{{asset('img/logo.png')}}" alt="logo" class="logo logo-img">
                    </a>
                </div>
            </div>

            @if(Request()->path() == '/' || Request()->path() == "login" ||
                Request()->path() == "login_estudiante")
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="sign-out">
                        &nbsp;
                    </div>
                </div>
            @else
                @include('layouts.menu')
            @endif
        </div>

        @yield('content')

    </div>

    @include('layouts.footer')
</body>
</html>
