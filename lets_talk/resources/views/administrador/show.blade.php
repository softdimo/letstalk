@extends('layouts.layout')
@section('title', 'Show')
@section('css')
@stop

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1 class="text-center text-uppercase">Details User {{$usuario->nombres}}</h1>
    </div>
</div>

<div class="row m-b-30 m-t-30 padding-border">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <a class="btn btn-warning"
            href="{{route('administrador.index')}}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back
        </a>
    </div>
</div>

<hr>
<div class="padding-border">
    @include('administrador.fields_show')
</div>

@include('layouts.loader')

@stop
@section('scripts')
<script>
    $(document).ready(function()
    {
        setTimeout(() => {
            $("#loaderGif").hide();
            $("#loaderGif").addClass('ocultar');

            $("#longitud_doc").hide();
            $("#longitud_doc").addClass('ocultar');
        }, 1500);

        window.$(".select2").prepend(new Option("Select Contact...", "-1"));
        $("#nombres").trigger('focus');
        $("#apellidos").trigger('focus');
        $("#id_municipio_nacimiento").trigger('focus');
        $("#id_tipo_documento").trigger('focus');
        $("#numero_documento").trigger('focus');
        $("#id_municipio_nacimiento").trigger('focus');
        $("#fecha_nacimiento").trigger('focus');
        $("#estado").trigger('focus');
        $("#telefono").trigger('focus');
        $("#celular").trigger('focus');
        $("#direccion_residencia").trigger('focus');
        $("#correo").trigger('focus');
        $("#zoom_clave").trigger('focus');
        $("#genero").trigger('focus');
        $("#id_municipio_residencia").trigger('focus');
        $("#id_rol").trigger('focus');
        $("#id_nivel").trigger('focus');
        $("#id_tipo_ingles").trigger('focus');

        let id_rol = $("#id_rol").val();

        if (id_rol == 3 || id_rol == "3")
        {
            $("#div_nivel").show('slow');
            $("#div_nivel").removeClass('ocultar');
            $("#div_tipo_ing").hide('slow');
            $("#div_tipo_ing").addClass('ocultar');

            $("#id_nivel").trigger('focus');

        } else {

            $("#div_nivel").hide('slow');
            $("#div_nivel").addClass('ocultar');
            $("#div_tipo_ing").show('slow');
            $("#div_tipo_ing").removeClass('ocultar');

            $("#id_tipo_ingles").trigger('focus');
        }
    });

    $("#id_rol").change(function()
    {
        let id_rol = $("#id_rol").val();

        if (id_rol == 3 || id_rol == "3") {
            $("#div_nivel").show('slow');
            $("#div_nivel").removeClass('ocultar');
            $("#div_tipo_ing").hide('slow');
            $("#div_tipo_ing").addClass('ocultar');

            $("#id_nivel").trigger('focus');

        } else {

            $("#div_nivel").hide('slow');
            $("#div_nivel").addClass('ocultar');
            $("#div_tipo_ing").show('slow');
            $("#div_tipo_ing").removeClass('ocultar');

            $("#id_tipo_ingles").trigger('focus');
        }
    });

</script>
@endsection
