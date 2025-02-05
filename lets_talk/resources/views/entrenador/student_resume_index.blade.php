@extends('layouts.layout')
@section('title', 'Student Resume')

@section('css')
    <link href="{{asset('DataTable/datatables.min.css')}}" rel="stylesheet">

    <style>
        .left-align{
            text-align:left;
        }

        .right-align{
            text-align: right;
        }

        .center-align{
            text-align: center !important;
        }

        .color-low{
            color:#31ED2D;
        }

        .color-mid{
            color:#EABC19;
        }

        .color-hi{
            color:#FF0000;
        }
        .color-sin{
            color:black;
        }

        .w50{
            width: 50%;
        }

        .w100{
            width: 100%;
        }
        .gral-font{
            font-family: Roboto;
            font-size: 20px;
            font-weight: 400;
            letter-spacing: 0em;
            text-align: left;
        }
        .margin-top{
            margin-top: 1rem;
        }

        .margin-bottom{
            margin-bottom: 3rem;
        }

        .margin-y{
            margin-top: 5rem;
            margin-bottom: 2rem;
        }
        textarea {
            resize: none;
            background: #ECF3FF;
            box-shadow: 0px 4px 4px 0px #0000004D inset;
        }
        .btn-evaluation {
            font-family: Encode Sans;
            font-size: 18px;
            font-weight: 400;
            line-height: 23px;
            letter-spacing: 0em;
            background: #FFFFFF;
            border: 1px solid #FFFFFF;
            color: white;
            box-shadow: 0px 4px 4px 0px #00000040;
            padding: 1rem
        }
        .flex{
            display: flex;
        }
        .flex-start{
            justify-content: flex-start;
        }
        .flex-end{
            justify-content: flex-end !important;
        }
        table{
            table-layout: fixed;
            width: 100%;
            border-collapse:separate !important;
            background: #ECF3FF;
            border-spacing: 50px;
            /* cellspacing:100px; */
            /* font-weight:bold; */
        }
        th, td {
            word-wrap: break-word;
        }
        .swal2-cancel {
            background-color: #1D9BF0;
            padding: 1rem !important;
            color: #FFF !important;
            box-shadow: 0px 4px 4px 0px #00000040;
        }
    </style>
@stop

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <div class="row">
        <div class="col-12">
            <h1 class="text-center text-uppercase">Student Resume</h1>
        </div>
    </div>

    <div class="row p-b-20 float-right" style="padding-left:5rem;padding-right:5rem;">
        <div class="col-xs-12 col-sm-12 col-md-12">
            {{-- <a href="{{route('administrador.create')}}" class="btn btn-primary">Create New User</a> --}}
        </div>
    </div>

    <div class="row p-t-30" style="padding-left:5rem;padding-right:5rem;">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover w-100" id="tbl_student_resume" aria-describedby="Student Resume">
                    <thead>
                        <tr class="header-table">
                            <th>Names</th>
                            <th>User</th>
                            <th>Whatsapp</th>
                            <th>Rol</th>
                            <th>Document Type</th>
                            <th>Document Number</th>
                            <th>Email</th>
                            <th>System Entry Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estudiantes as $estudiante)
                            @php
                                $estudiante->fecha_ingreso_sistema = Carbon::createFromTimestamp($estudiante->fecha_ingreso_sistema)->format('d/m/Y');
                            @endphp
                            <tr>
                                <td>{{$estudiante->nombre_completo}}</td>
                                <td>{{$estudiante->usuario}}</td>
                                <td>{{$estudiante->celular}}</td>
                                <td>{{$estudiante->rol}}</td>
                                <td>{{$estudiante->tipo_documento}}</td>
                                <td>{{$estudiante->numero_documento}}</td>
                                <td>{{$estudiante->correo}}</td>
                                <td>{{$estudiante->fecha_ingreso_sistema}}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" onclick="verEstudiante({{$estudiante->id_user}})">
                                        See Student
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- @include('layouts.loader') --}}
@stop

@section('scripts')
    <script src="{{asset('DataTable/pdfmake.min.js')}}"></script>
    <script src="{{asset('DataTable/vfs_fonts.js')}}"></script>
    <script src="{{asset('DataTable/datatables.min.js')}}"></script>

    <script type="text/javascript">
        $(document ).ready(function() {
            $('#tbl_student_resume').DataTable({
                'ordering': false,
                "lengthMenu": [[10,25,50,100, -1], [10,25,50,100, 'ALL']],
                dom: 'Blfrtip',
                "info": "Showing page _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros",
                "buttons": [
                    {
                        extend: 'copyHtml5',
                        text: 'Copiar',
                        className: 'waves-effect waves-light btn-rounded btn-sm btn-primary',
                        init: function(api, node, config) {
                            $(node).removeClass('dt-button')
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'waves-effect waves-light btn-rounded btn-sm btn-primary',
                        init: function(api, node, config) {
                            $(node).removeClass('dt-button')
                        }
                    },
                ]
            });
        });

        function verEstudiante(idStudent) {
            $.ajax({
                async: true,
                url: "{{route('estudiante_hoja_vida')}}",
                type: "POST",
                datatype: "JSON",
                data: {'id_estudiante': idStudent},
                beforeSend: function() {
                    $("#loaderGif").show();
                    $("#loaderGif").removeClass('ocultar');
                },
                success: function (respuesta) {

                    if(respuesta == "error_exception") {
                        $("#loaderGif").hide();
                        $("#loaderGif").addClass('ocultar');
                        Swal.fire(
                            'Error',
                            'An error occurred, contact support.',
                            'error'
                        );
                        return;
                    }

                    let fechaNacimiento = respuesta.fecha_nacimiento;
                    fechaNacimiento = new Date(fechaNacimiento * 1000).toLocaleDateString('es-ES');

                    let fechaIngresoSistema = respuesta.fecha_ingreso_sistema;
                    fechaIngresoSistema = new Date(fechaIngresoSistema * 1000).toLocaleDateString('es-ES');

                    html = `<p class="gral-font center-align"><strong>STUDENT RESUME</strong></p>`;

                    html += `
                            <div class="d-flex flex-row margin-top" style="padding:0; width:100%;">
                                <div style="width:50%">
                                    <p class="gral-font" style="color:#33326C;">${respuesta.nombre_completo}</p>
                                </div>
                    `;
                                if (respuesta.nivel_descripcion) {
                                    if (respuesta.nivel_descripcion == "LOW") {
                                        html += `<div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w50">Level:</p>
                                                    <p class="color-low gral-font center-align w-100">${respuesta.nivel_descripcion}</p>
                                                 </div>
                                        `;
                                    } else if (respuesta.nivel_descripcion == "MID") {
                                        html += `<div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w50">Level:</p>
                                                    <p class="color-mid gral-font center-align w-100">${respuesta.nivel_descripcion}</p>
                                                 </div>
                                        `;
                                    } else if (respuesta.nivel_descripcion == "HI") {
                                        html += `<div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w50">Level:</p>
                                                    <p class="color-hi gral-font center-align w-100">${respuesta.nivel_descripcion}</p>
                                                 </div>
                                        `;
                                    } else {
                                        html += `<div class="d-flex flex-row" style="padding:0; width:50%;">
                                                <p class="gral-font w50">Level:</p>
                                                <p class="center-align gral-font w-100">${respuesta.nivel_descripcion}</p>
                                            </div>
                                        `;
                                    }
                                } else {
                                    html += `<div class="d-flex flex-row" style="padding:0; width:50%;">
                                                <p class="gral-font w-100">LEVEL:</p>
                                            </div>
                                    `;
                                }
                    html += `
                            </div>
                    `;

                    html += `
                            <div class="d-flex flex-row margin-top" style="padding:0; width:100%;">
                                <div style="width:50%">
                                    <p class="gral-font" style="color:#33326C;">User</p>
                                </div>
                    `;
                                if (respuesta.usuario) {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100">${respuesta.usuario}</p>
                                                </div>
                                    `;
                                } else {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100"></p>
                                                </div>
                                    `;
                                }
                    html += `
                            </div>
                    `;

                    html += `
                            <div class="d-flex flex-row margin-top" style="padding:0; width:100%;">
                                <div style="width:50%">
                                    <p class="gral-font" style="color:#33326C;">Whatsapp</p>
                                </div>
                    `;
                                if (respuesta.celular) {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100">${respuesta.celular}</p>
                                                </div>
                                    `;
                                } else {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100"></p>
                                                </div>
                                    `;
                                }
                    html += `
                            </div>
                    `;

                    html += `
                            <div class="d-flex flex-row margin-top" style="padding:0; width:100%;">
                                <div style="width:50%">
                                    <p class="gral-font" style="color:#33326C;">Email</p>
                                </div>
                    `;
                                if (respuesta.correo) {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100">${respuesta.correo}</p>
                                                </div>
                                    `;
                                } else {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100"></p>
                                                </div>
                                    `;
                                }
                    html += `
                            </div>
                    `;

                    html += `
                            <div class="d-flex flex-row margin-top" style="padding:0; width:100%;">
                                <div style="width:50%">
                                    <p class="gral-font" style="color:#33326C;">Document type</p>
                                </div>
                    `;
                                if (respuesta.tipo_documento) {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100">${respuesta.tipo_documento}</p>
                                                </div>
                                    `;
                                } else {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100"></p>
                                                </div>
                                    `;
                                }
                    html += `
                            </div>
                    `;

                    html += `
                            <div class="d-flex flex-row margin-top" style="padding:0; width:100%;">
                                <div style="width:50%">
                                    <p class="gral-font" style="color:#33326C;">Document</p>
                                </div>
                    `;
                                if (respuesta.numero_documento) {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100">${respuesta.numero_documento}</p>
                                                </div>
                                    `;
                                } else {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100"></p>
                                                </div>
                                    `;
                                }
                    html += `
                            </div>
                    `;

                    html += `
                            <div class="d-flex flex-row margin-top" style="padding:0; width:100%;">
                                <div style="width:50%">
                                    <p class="gral-font" style="color:#33326C;">Roll</p>
                                </div>
                    `;
                                if (respuesta.rol) {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100">${respuesta.rol}</p>
                                                </div>
                                    `;
                                } else {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100"></p>
                                                </div>
                                    `;
                                }
                    html += `
                            </div>
                    `;

                    html += `
                            <div class="d-flex flex-row margin-top" style="padding:0; width:100%;">
                                <div style="width:50%">
                                    <p class="gral-font" style="color:#33326C;">Date of Birth</p>
                                </div>
                    `;
                                if (respuesta.fecha_nacimiento) {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100">${fechaNacimiento}</p>
                                                </div>
                                    `;
                                } else {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100"></p>
                                                </div>
                                    `;
                                }
                    html += `
                            </div>
                    `;

                    html += `
                            <div class="d-flex flex-row margin-top" style="padding:0; width:100%;">
                                <div style="width:50%">
                                    <p class="gral-font" style="color:#33326C;">System entry Date</p>
                                </div>
                    `;
                                if (respuesta.fecha_ingreso_sistema) {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100">${fechaIngresoSistema}</p>
                                                </div>
                                    `;
                                } else {
                                    html += `   <div class="d-flex flex-row" style="padding:0; width:50%;">
                                                    <p class="gral-font w-100"></p>
                                                </div>
                                    `;
                                }
                    html += `
                            </div>
                    `;

                    Swal.fire({
                        html: html,
                        showCloseButton: true,
                        showCancelButton: false,
                        showConfirmButton: false,
                        focusConfirm: false,
                        allowOutsideClick: false,
                        width: 600,
                        padding: '3em',
                        background: '#fff',
                    });
                }
            })
        }
    </script>
@endsection
