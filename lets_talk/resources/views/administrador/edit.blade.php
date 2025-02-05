@extends('layouts.layout')
@section('title', 'Edit')
@section('css')
@stop
@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1 class="text-center text-uppercase">Edit User</h1>
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
{!! Form::model($usuario, ['method' => 'PUT',
    'route' => ['administrador.update', $usuario->id_user],
    'class' => 'login100-form validate-form padding-border', 'id' => 'form_edit_user', 'autocomplete' => 'off']) !!}
    @include('administrador.fields')
{!! Form::close() !!}

@include('layouts.loader')

@stop

@section('scripts')
    {{-- <script src="{{asset('validate/jquery.min.js')}}"></script> --}}
    {{-- <script src="{{asset('validate/validate.min.js')}}"></script> --}}

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
        $("#correo").trigger('focus');
        $("#celular").trigger('focus');
        $("#direccion_residencia").trigger('focus');
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

        } else
        {
            $("#div_nivel").hide('slow');
            $("#div_nivel").addClass('ocultar');
            $("#div_tipo_ing").show('slow');
            $("#div_tipo_ing").removeClass('ocultar');

            $("#id_tipo_ingles").trigger('focus');
        }
    });

    $("#numero_documento").blur(function()
    {
        let num_doc = $("#numero_documento").val();
        let id_usuario = $("#id_usuario").val();
        let tipo_doc = $("#id_tipo_documento").val();

        if(num_doc.length < 6)
        {
            $("#longitud_doc").show('slow');
            $("#longitud_doc").removeClass('ocultar');
            $("#numero_documento").val(num_doc);

            return;
        } else
        {
            $("#longitud_doc").hide();
            $("#longitud_doc").addClass('ocultar');
        }

        $.ajax({
            async: true
            , url: "{{route('validar_cedula_edicion')}}"
            , type: "POST"
            , dataType: "json"
            , data: {
                'numero_documento': num_doc
                , 'id_usuario': id_usuario
                , 'tipo_documento': tipo_doc
            }
            , beforeSend: function() {
                $("#loaderGif").show();
                $("#loaderGif").removeClass('ocultar');
            }
            , success: function(response)
            {
                if (response == "existe_doc") {
                    $("#loaderGif").hide();
                    $("#loaderGif").addClass('ocultar');
                    $("#numero_documento").val('');

                    Swal.fire({
                        position: 'center'
                        , title: 'Info!'
                        , html: 'There is already a record with the document number entered!'
                        , icon: 'info'
                        , type: 'info'
                        , showCancelButton: false
                        , showConfirmButton: false
                        , allowOutsideClick: false
                        , allowEscapeKey: false
                        , timer: 5000
                    });

                    return;
                }

                if (response == "error_exception")
                {
                    $("#loaderGif").hide();
                    $("#loaderGif").addClass('ocultar');
                    Swal.fire({
                        position: 'center'
                        , title: 'Error!'
                        , html: 'An error occurred, contact support!'
                        , icon: 'error'
                        , type: 'error'
                        , showCancelButton: false
                        , showConfirmButton: false
                        , allowOutsideClick: false
                        , allowEscapeKey: false
                        , timer: 5000
                    });

                    return;
                }

                if (response == "no_existe_doc")
                {
                    $("#loaderGif").hide();
                    $("#loaderGif").addClass('ocultar');
                }
            }
        });
    });

    $("#correo").blur(function()
    {
        let correo = $("#correo").val();
        let id_usuario = $("#id_usuario").val();

        $.ajax({
            async: true
            , url: "{{route('validar_correo_edicion')}}"
            , type: "POST"
            , dataType: "json"
            , data: {
                'email': correo
                , 'id_usuario': id_usuario
            }
            , beforeSend: function() {
                $("#loaderGif").show();
                $("#loaderGif").removeClass('ocultar');
            }
            , success: function(response)
            {
                if (response == "existe_correo")
                {
                    $("#loaderGif").hide();
                    $("#loaderGif").addClass('ocultar');
                    $("#correo").val('');

                    Swal.fire({
                        position: 'center'
                        , title: 'Info!'
                        , html: 'A similar email already exists in our database!'
                        , icon: 'info'
                        , type: 'info'
                        , showCancelButton: false
                        , showConfirmButton: false
                        , allowOutsideClick: false
                        , allowEscapeKey: false
                        , timer: 5000
                    });

                    return;
                }

                if (response == "error_exception_correo")
                {
                    $("#loaderGif").hide();
                    $("#loaderGif").addClass('ocultar');
                    Swal.fire({
                        position: 'center'
                        , title: 'Error!'
                        , html: 'An error occurred, contact support!'
                        , icon: 'error'
                        , type: 'error'
                        , showCancelButton: false
                        , showConfirmButton: false
                        , allowOutsideClick: false
                        , allowEscapeKey: false
                        , timer: 5000
                    });

                    return;
                }

                if (response == "no_existe_correo")
                {
                    $("#loaderGif").hide();
                    $("#loaderGif").addClass('ocultar');
                }
            }
        });
    });

    $("#id_rol").change(function()
    {
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

    setTimeout(() =>
    {
        $("#longitud_doc").hide();
        $("#longitud_doc").addClass('ocultar');
    }, 1500);
</script>
@endsection
