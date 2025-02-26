
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
                <h1 class="text-center text-uppercase">help</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2">
                <p class="text-uppercase">
                   <h3>Compra Créditos</h3>
                   <span class="text-justify">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt ipsa dicta inventore et voluptate error incidunt, voluptas quos molestiae illo vitae suscipit porro beatae quod, magnam asperiores minus doloremque officia?
                   </span>
                </p>
            </div>
        </div>

        <div class="row m-t-25">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2">
                <p class="text-uppercase">
                   <h3>Preguntas Generales</h3>
                   <span class="text-justify">
                   Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eum amet ut, culpa sit nihil quae cumque sapiente, reiciendis modi, est vero magni. Ea minima eaque consectetur earum a nesciunt vel?
                </p>
            </div>
        </div>
    </div>

    @include('layouts.footer')

