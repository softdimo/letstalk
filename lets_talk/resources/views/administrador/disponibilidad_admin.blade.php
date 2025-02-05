@extends('layouts.layout')
@section('title', 'Trainers Schedule')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/dataTables.bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/fixedHeader.bootstrap.min.css')}}">
    <link href="{{asset('DataTable/datatables.min.css')}}" rel="stylesheet">

    <style>
        .btnSch {
            margin-top: 2rem;
            margin-bottom: 5rem;
        }
        .lb-time{
            font-size: 14px;
        }
        .hour{
            text-align: center;
            font-size: 14px;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <h2 class="text-center text-uppercase">Trainer's Schedule</h2>
        </div>
    </div>

    <div class="row p-b-20 float-left" style="padding-left:5rem;padding-right:5rem;">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <button class="btn btn-primary" onclick="crearHorario()">Create New Schedule</button>
        </div>
    </div>

    <div class="row p-t-30" style="padding-left:5rem;padding-right:5rem;">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover"
                        id="tbl_trainer_sessions" aria-describedby="tabla horarios entrenadores">
                    <thead>
                        <tr class="header-table">
                            <th>ID</th>
                            <th>Schedule</th>
                            <th>State</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($todasDisponibilidades as $horario)
                        <tr>
                            <td>{{$horario['id_horario']}}</td>
                            <td>{{$horario['horario']}}</td>
                            <td>
                                @if($horario['id_estado'] == 1 )
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($horario['id_estado'] == 1 )
                                    <a href="#" class="btn btn-sm btn-warning"
                                        id="btn_inactive_schedule_{{$horario['id_horario']}}"
                                        onclick="changeStateSchedule('{{$horario['id_horario']}}')">Inactivate Schedule</a>
                                @else
                                    <a href="#" class="btn btn-sm btn-success"
                                        id="btn_inactive_schedule_{{$horario['id_horario']}}"
                                        onclick="changeStateSchedule('{{$horario['id_horario']}}')">Activate Schedule</a>
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
    <script src="{{asset('js/jquery-2.1.3.min.js')}}"></script>
    <script src="{{asset('js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('js/dataTables.fixedHeader.min.js')}}"></script>

    <script>
        $(document).ready(function() {

            setTimeout(() => {
                $("#loaderGif").hide();
                $("#loaderGif").addClass('ocultar');
            }, 1500);

            $('#tbl_trainer_sessions').DataTable({
                'ordering': false
            });

            $('#btn_create_schedule').click(function()
            {
                Swal.fire({
                    position: 'center',
                    title: 'Info!',
                    html: form_shedule,
                    icon: 'info',
                    type: 'info',
                    showCancelButton: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                });
            });
        }); // FIN ready

        function validarHoras()
        {
            let initial_hour = $('#initial_hour').val();
            let final_hour = $('#final_hour').val();

            initial_hour = initial_hour.replace(':', '');
            final_hour = final_hour.replace(':', '');

            inicial_hora = initial_hour.substr(0, 2);
            inicial_minutos = initial_hour.substr(2, 2);

            final_hora = final_hour.substr(0, 2);
            final_minutos = final_hour.substr(2, 2);

            horaInicialCompleta = (parseInt(inicial_hora*60)) + (parseInt(inicial_minutos));
            horaFinalCompleta = (parseInt(final_hora*60)) + (parseInt(final_minutos));

            let diferencia = horaFinalCompleta - horaInicialCompleta;

            if (diferencia > 31)
            {
                Swal.fire(
                    'Error!',
                    'The schedule cannot exceed 30 minutes!',
                    'error'
                );
                return;
            }

            if(diferencia <= 0 || diferencia <= 29)
            {
                Swal.fire(
                    'Error!',
                    'The schedule entered is invalid!',
                    'error'
                );
                return;
            }
        }

        function crearHorario()
        {
            let form_shedule = ''
    
            form_shedule += `
                    <div style="margin-top:2rem;">
                        <label class="lb-time">Initial Hour</label>
                        <input type="time" min="08:00" max="20:30"
                                name="initial_hour" id="initial_hour" step="1800" class="hour" required>
                    </div>

                    <div>
                        <label class="lb-time">Final Hour</label>
                        <input type="time" min="08:00" max="20:00"
                                name="final_hour" id="final_hour" step="1800" class="hour"
                                onblur="validarHoras()" required>
                    </div>

                    <div class="alert alert-danger ocultar" role="alert" id="schedule_alert">
                        The fields Initial Hour and Final Hour are required
                    </div>
            `;

            form_shedule += `<img  class="ocultar" src="{{asset('img/loading.gif')}}" id="loading_ajax" alt="loading..." />`;

            form_shedule += `
                    <input type="button" class="btn btn-primary btnSch" id="btn_store_shedule" value="Create Schedule" >
            `;

            Swal.fire({
                title: 'Create Scheduler',
                html: form_shedule,
                icon: 'success',
                type: 'success',
                showConfirmButton: false,
                focusConfirm: false,
                showCloseButton: true,
                showCancelButton: false,
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
            });

            $('#btn_store_shedule').on('click', function ()
            {
                validarHoras();

                let initial_hour = $('#initial_hour').val();
                let final_hour = $('#final_hour').val();

                if(initial_hour == "" || final_hour == "")
                {
                    $('#initial_hour').attr('required', true);
                    $('#final_hour').attr('required', true);
                    $("#schedule_alert").show();
                    $("#schedule_alert").removeClass('ocultar');
                } else
                {
                    $('#btn_store_shedule').attr('disabled',true);
                    $("#schedule_alert").hide();
                    $("#schedule_alert").addClass('ocultar');

                    $.ajax({
                        async: true,
                        url: "{{route('administrador.disponibilidad_admin_store')}}",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            'hora_inicial': initial_hour,
                            'hora_final': final_hour
                        },
                        beforeSend: function ()
                        {
                            $("#loading_ajax").show();
                            $("#loading_ajax").removeClass('ocultar');
                        },
                        success: function(response)
                        {
                            $("#loading_ajax").hide();
                            $("#loading_ajax").addClass('ocultar');

                            if (response == "success")
                            {
                                $("#loading_ajax").hide();
                                $("#loading_ajax").addClass('ocultar');

                                Swal.fire(
                                    'Great!',
                                    'Schedule successfully created!',
                                    'success'
                                );

                                window.location.reload();
                                return;
                            }

                            if (response == "error")
                            {
                                $("#loading_ajax").hide();
                                $("#loading_ajax").addClass('ocultar');

                                Swal.fire(
                                    'Error!',
                                    'An error accurred, try again!',
                                    'error'
                                );

                                window.location.reload();
                                return;
                            }

                            if (response == "exception")
                            {
                                $("#loading_ajax").hide();
                                $("#loading_ajax").addClass('ocultar');

                                Swal.fire(
                                    'Error!',
                                    'An error has occurred of database creating the Schedule,'
                                    + ' try again, if the problem persists contact support.!',
                                    'error'
                                );

                                window.location.reload();
                                return;
                            }

                            if (response == "schedule_exist")
                            {
                                $("#loading_ajax").hide();
                                $("#loading_ajax").addClass('ocultar');

                                Swal.fire(
                                    'Error!',
                                    'The Schedule already exists, choose another one please',
                                    'error'
                                );

                                window.location.reload();
                                return;
                            }
                        }
                    });
                }

                setTimeout(() => {
                    $("#schedule_alert").hide();
                    $("#schedule_alert").addClass('ocultar');
                }, 6000);

            });
        }

        function changeStateSchedule(idHorario)
        {
            Swal.fire({
                position: 'center'
                , title: 'Are you sure you want to change the state of this schedule?'
                , text: 'You will not be able to revert this!'
                , icon: 'warning'
                , type: 'warning'
                , showCancelButton: true
                , showConfirmButton: true
                , allowOutsideClick: false
                , allowEscapeKey: false
                , confirmButtonText: '<i class="fa fa-thumbs-up"></i> Yes, Change!'
                , cancelButtonText: '<i class="fa fa-thumbs-down"></i> No, Cancel'
                , cancelButtonColor: '#CCC'
                , confirmButtonColor: '#0bc64a'
            }).then((result) =>
            {
                if (result.value == true)
                {
                    $.ajax({
                        async: true
                        , url: "{{route('disponibilidad_admin_state')}}"
                        , type: "POST"
                        , dataType: "json"
                        , data: {
                            'id_horario': idHorario
                        }
                        ,
                        beforeSend: function() {
                            $("#loaderGif").show();
                            $("#loaderGif").removeClass('ocultar');
                        }
                        , success: function(response)
                        {
                            if (response == "success")
                            {
                                $("#loaderGif").hide();
                                $("#loaderGif").addClass('ocultar');
                                Swal.fire({
                                    position: 'center'
                                    , title: 'Successfull Process!'
                                    , html: 'The scheduling status has been successfully modified'
                                    + ' and all availabilities associated with this schedule have also been changed.!'
                                    , icon: 'info'
                                    , type: 'success'
                                    , showCancelButton: false
                                    , showConfirmButton: false
                                    , allowOutsideClick: false
                                    , allowEscapeKey: false
                                    , timer: 3000
                                });
                                setTimeout('window.location.reload()', 3500);
                                return;
                            }

                            if (response == "no_inactived")
                            {
                                $("#loaderGif").hide();
                                $("#loaderGif").addClass('ocultar');
                                Swal.fire({
                                    position: 'center'
                                    , title: 'Error!'
                                    , html: 'There was a problem change the state of the Schedule!'
                                    , icon: 'error'
                                    , type: 'error'
                                    , showCancelButton: false
                                    , showConfirmButton: false
                                    , allowOutsideClick: false
                                    , allowEscapeKey: false
                                    , timer: 3000
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
                                    , html: 'There was a problem of database change the state of the Schedule!'
                                    , icon: 'error'
                                    , type: 'error'
                                    , showCancelButton: false
                                    , showConfirmButton: false
                                    , allowOutsideClick: false
                                    , allowEscapeKey: false
                                    , timer: 3000
                                });
                                return;
                            }

                            if(response == "home")
                            {
                                $("#loaderGif").hide();
                                $("#loaderGif").addClass('ocultar');
                                window.location.href = `${window.location.hostname}:${window.location.port}`;
                                return;
                            }
                        }
                    });
                } else
                {
                    $("#loaderGif").hide();
                    $("#loaderGif").addClass('ocultar');
                    Swal.fire({
                        position: 'center'
                        , title: 'Info!'
                        , html: 'No changes were made!'
                        , icon: 'info'
                        , type: 'info'
                        , showCancelButton: false
                        , showConfirmButton: false
                        , allowOutsideClick: false
                        , allowEscapeKey: false
                        , timer: 3000
                    });
                    return;
                }
            });
        }
    </script>
@endsection
