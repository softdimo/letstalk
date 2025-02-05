

@include('layouts.header')
@section('title', 'Show')
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
                <h1 class="text-center text-uppercase">Services</h1>
            </div>
        </div>

        <div class="row m-t-20">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2">
                <p class="text-justify">
                    Somos pioneros en una nueva modalidad de enseñanza de Inglés. No somos una academia de Inglés más.
                    <br>
                    Estamos enfocados en el diálogo, de esta manera traer a tu casa la experiencia del inglés en el extranjero sin vivir
                    allá. Preparamos a nuestros estudiantes, orientándolos a cualquier tipo de conversación.
                    <br>
                    Tenemos la mejor técnica para interactuar con nuestros pupilos.
                    Nuestro equipo de entrenadores (el crew) es el más capacitado! con experiencia en múltiples áreas,
                    para así satisfacer las necesidades de los estudiantes, conviertiendo la sesión de aprendizaje en un hambiente
                    prósper y entretenido.
                </p>
            </div>
        </div>
    </div>
@include('layouts.footer')
