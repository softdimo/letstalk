@extends('layouts.layout')
@section('title', 'Mis Sesiones')
@section('css')
    <link href="{{asset('DataTable/datatables.min.css')}}" rel="stylesheet">
@stop

@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="row">
        <div class="col-12">
            <h1 class="text-center text-uppercase">Mis Sesiones</h1>
        </div>
    </div>

    <div class="row p-b-20 float-left" style="padding-left:3rem;padding-right:5rem;">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <a href="{{route('estudiante.disponibilidad')}}" class="btn btn-primary">Atrás Disponibilidad</a>
        </div>
    </div>

    <div class="row p-t-30" style="padding-left:5rem;padding-right:5rem;">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="tbl_reservas">
                    <thead>
                        <tr class="header-table">
                            <th>Entrenador</th>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Link Meet</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($misSesiones as $sesion)
                            @php
                                $idEvento = $sesion->id_trainer_horario;
                                $idInstructor = $sesion->id_instructor;
                                $idEstudiante = $sesion->id_estudiante;
                                $idEstado = 8;
                            @endphp

                            <tr>
                                <td>{{$sesion->nombre_instructor}}</td>
                                <td>{{$sesion->start_date}}</td>
                                <td>{{$sesion->start_time}}</td>
                                <td><a href="{{$sesion->link_meet}}" target="_blank" class="text-primary">{{$sesion->link_meet}}</a></td>
                                <td>
                                    @php
                                        $diaHoy = Carbon::now();
                                        $diaClase = Carbon::createFromFormat('Y-m-d H:i', $sesion->start_date . ' ' . $sesion->start_time);
                                        $diaClaseMenosUnaHora = $diaClase->copy()->subHour(); // Restamos una hora al inicio de la clase
                                    @endphp

                                    @if($diaClaseMenosUnaHora > $diaHoy)
                                        <button type="button" class="text-white btn btn-warning" onclick="cancelarClase('{{$idEvento}}','{{$idInstructor}}','{{$idEstudiante}}','{{$idEstado}}')">CANCELAR</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
        $(document).ready(function()
        {
            setTimeout(() => {
                $("#loaderGif").hide();
                $("#loaderGif").addClass('ocultar');
            }, 1500);
            
            $('#tbl_reservas').DataTable({
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

        // ===============================================================

        function cancelarClase(idHorario, idInstructor, idEstudiante, idEstado)
        {
            Swal.fire({
                title: '¿Realmente quiere cancelar esta clase?',
                html: 'Deberá crearla nuevamente si cambia de opinión',
                icon: 'warning',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value)
                {
                    $.ajax({
                        async: true,
                        url: "{{route('estudiante.cancelar_clase')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'id_horario': idHorario,
                            'id_instructor': idInstructor,
                            'id_estudiante': idEstudiante,
                            'id_estado': idEstado
                        },
                        beforeSend: function() {
                            $("#loaderGif").show();
                            $("#loaderGif").removeClass('ocultar');
                        },
                        success: function(response)
                        {
                            console.log(response);

                            $("#loaderGif").hide();
                            $("#loaderGif").addClass('ocultar');

                            if(response.status === 'auth_required')
                            {
                                window.location.href = response.auth_url;
                                return;
                            }

                            // Define variables para el título, texto y tipo de alerta
                            let title, text, type;

                            // Verifica el estado de la respuesta y asigna valores adecuados
                            if (response.status === "error_link")
                            {
                                title = 'Error!';
                                text = 'Link Meet NO Cancelado!';
                                type = 'error';
                            } else if (response.status === "error_exception")
                            {
                                title = 'Error!';
                                text = 'Error Exception!';
                                type = 'error';
                            } else
                            {
                                title = 'Error!';
                                text = 'Error al cancelar la clase!';
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
                            $("#loaderGif").hide();
                            $("#loaderGif").addClass('ocultar');

                            Swal.fire({
                                title: 'Error!',
                                text: 'Ocurrió un error al cancelar la clase. Inténtelo de nuevo más tarde.',
                                type: 'error'
                            });
                        }
                    }); // Fin ajax
                } // FIN if
            }); // FIN then de Swal.Fire
        } // FIN cancelarClase
    </script>
@endsection
