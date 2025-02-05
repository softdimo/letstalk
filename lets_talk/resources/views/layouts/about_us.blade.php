
@include('layouts.header')
@section('title', 'About Us')
@section('css')
@stop
@section('content')

    <div class="contenido-cuerpo">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="logo-box">
                    <a href="{{route('home')}}">
                        <img src="{{asset('img/logo.png')}}" alt="logo" class="logo logo-img">
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <h1 class="text-center text-uppercase">About Us</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2">
                <p class="text-uppercase">
                   <h3>Misión</h3>
                   <span class="text-justify">
                    Somos una organización con fin de preparar a los Talkers para usar Inglés cotidiano.
                    Let’s Talk nace gracias a la necesidad de práctica que nos dejan las academias.
                    <br>
                    Utilizando como herramienta de práctica las llamadas de voz para crear el entorno perfecto, como sí vivieses en el exterior.
                   </span>
                </p>
            </div>
        </div>

        <div class="row m-t-25">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2">
                <p class="text-uppercase">
                   <h3>Visión</h3>
                   <span class="text-justify">
                    Llegar cada vez a más personas por diferentes medios, de esta manera, crecer como organización.
                    <br>
                    Incrementar nuestro talento huamano y de este modo aumentar el alcance de nuestras clases para llegar
                    a todos los países de habla latina.
                </p>
            </div>
        </div>
    </div>

    @include('layouts.footer')

