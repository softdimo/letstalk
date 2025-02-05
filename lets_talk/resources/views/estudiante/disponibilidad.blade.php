@extends('layouts.layout')
@section('title', 'Reservar Clase')
@section('css')
    <link href="{{asset('DataTable/datatables.min.css')}}" rel="stylesheet">
@stop

@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    
    <div class="row">
        <div class="col-12">
            <h1 class="text-center text-uppercase">Semana</h1>
            <h2 class="text-center text-uppercase">Disponibilidad Entrenadores</h2>
        </div>
    </div>

    <div class="row p-b-20 float-left" style="padding-left:3rem;padding-right:5rem;">
        <div class="col-12">
            <a href="{{route('estudiante.index')}}" class="btn btn-primary">Reservas</a>
        </div>
    </div>

    <div class="row p-b-20 float-left" style="padding-left:3rem;padding-right:5rem;">
        <div class="col-12">
            <a href="{{route('estudiante.mis_creditos')}}" class="btn btn-primary">Mis Créditos</a>
        </div>
    </div>

    <div class="row m-b-30 m-t-30">
        <div class="col-12">
            <div class="border">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="tbl_disponibilidades" aria-describedby="sesiones entrenadores">
                        <thead>
                            <tr class="header-table">
                                <th>Entrenador</th>
                                <th>Fecha</th>
                                <th>Hora Inicio</th>
                                <th>Hora Final</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($disponibilidadEntrenadores as $disponibilidad)
                                @php
                                    $idEvento = $disponibilidad->id_evento;
                                    $idInstructor = $disponibilidad->id_instructor;
                                    $idEstudiante = $disponibilidad->id_estudiante;
                                    $FechaClase = $disponibilidad->start_date;
                                    $claseInicio = $disponibilidad->start_time;
                                    $claseFinal = $disponibilidad->end_time;
                                    $idEstado = $disponibilidad->id_estado;
                                @endphp
                                <tr>
                                    <td>{{$disponibilidad->nombre_completo}}</td>
                                    <td>{{$disponibilidad->start_date}}</td>
                                    <td>{{$disponibilidad->start_time}}</td>
                                    <td>{{$disponibilidad->end_time}}</td>
                                    <td>
                                        <button type="button" class="text-white"
                                                onclick="reservarClase('{{$idEvento}}','{{$idInstructor}}','{{$FechaClase}}','{{$claseInicio}}')"
                                                style="background-color: #21277B; padding:0.5em">RESERVAR YA
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.loader')
@stop

@section('scripts')
    <script src="{{asset('DataTable/pdfmake.min.js')}}"></script>
    <script src="{{asset('DataTable/vfs_fonts.js')}}"></script>
    <script src="{{asset('DataTable/datatables.min.js')}}"></script>

    <script>
        $( document ).ready(function()
        {
            $('#tbl_disponibilidades').DataTable({
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
            }); // FIN DataTable tbl_disponibilidades
        }); // FIN Document REady

        // ===============================================================

        function reservarClase(idHorario,idInstructor,FechaClase,claseInicio)
        {
            Swal.fire({
                title: '¿Realmente quiere reservar esta clase?',
                html: 'Puede proceder si está segur@ del horario y entrenador@',
                icon: 'info',
                type: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value)
                {
                    console.log(result.value);
                    $.ajax({
                        async: true,
                        url: "{{route('estudiante.reservar_clase')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'id_instructor': idInstructor,
                            'id_horario': idHorario,
                            'fecha_clase': FechaClase,
                            'hora_clase_inicio': claseInicio,
                        },
                        beforeSend: function() {
                            $("#loaderGif").show();
                            $("#loaderGif").removeClass('ocultar');
                        },
                        success: function(response)
                        {
                            $("#loaderGif").hide();
                            $("#loaderGif").addClass('ocultar');

                            if (response.status === "auth_required")
                            {
                                window.location.href = response.auth_url;
                                return;
                            }

                            // Define variables para el título, texto y tipo de alerta
                            let title, text, type;
                            
                            if (response.status === 'creditos_no_disponibles')
                            {
                                title = 'Info!';
                                text = 'No tiene créditos Disponibles!';
                                type = 'warning';
                            } else
                            {
                                title = 'Error!';
                                text = 'Ocurrió un error, inténtelo de nuevo. Si el problema persiste, comuníquese con el administrador!';
                                type = 'error';
                            }

                            // Mostrar alerta con Swal.fire
                            Swal.fire({
                                title: title,
                                text: text,
                                type: type
                            }).then(() => {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 3000);
                            });
                        }, // FIN Success
                        error: function(xhr, status, error) {
                            console.log("Error en la solicitud AJAX", error);
                            Swal.fire(
                                'Error!',
                                'Ocurrió un error en la comunicación con el servidor. Inténtelo de nuevo más tarde.',
                                'error'
                            );
                        } // Fin error: function()
                    }); // Fin ajax
                } // FIN if
            }); // FIN then de Swal.Fire
        } // FIN function reservarClase
    </script>
@endsection
