@extends('layouts.layout')
@section('title', 'Availability Trainers')

@section('css')
<link href="{{asset('DataTable/datatables.min.css')}}" rel="stylesheet">
@stop

@section('content')
<div class="row" style="padding-left:5rem;padding-right:5rem;">
    <div class="col-12">
        @if(session('rol') == 3)
            <h1 class="text-center text-uppercase">Disponibilidad Entrenadores</h1>
        @else

        <h1 class="text-center text-uppercase">Trainer's Availability</h1>

        <div class="mt-5">
            <a href="#" class="btn btn-sm btn-success"
                id="btn_aprove_all" onclick="actualizacionMasiva(1)">Approve All</a>
            <a href="#" class="btn btn-sm btn-warning"
                id="btn_reject_all" onclick="actualizacionMasiva(3)">Reject All</a>
            <a href="#" class="btn btn-sm btn-danger"
                id="btn_delete_all" onclick="actualizacionMasiva(4)">Delete All</a>

            @foreach ($disponibilidades as $disponibilidad)
                @php
                    $idDisponibilidad = $disponibilidad->id;
                @endphp
            @endforeach

        </div>
        @endif
    </div>
</div>

<div class="row p-b-20 float-right">
    <div class="col-xs-12 col-sm-12 col-md-12">

    </div>
</div>

<div class="row p-t-30" style="padding-left:5rem;padding-right:5rem;">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="tbl_availability">
                <thead>
                    <tr class="header-table">
                        <th>Id</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>Start Time</th>
                        <th>End Date</th>
                        <th>End Time</th>
                        <th>Trainer</th>
                        <th>State</th>
                        <th>Select all
                            @if(in_array(2, $arrayEstados))
                                <input type="checkbox" name="select_pending"
                                    id="select_pending" class="ml-3 form-check-input" style="margin-left:2rem;">
                            @endif
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($disponibilidades as $disponibilidad)
                        @if(session('rol') == 2)
                            @include('administrador.table_admin')
                        @endif
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
    //Variable global
    let idEventos;

    $(document).ready(function()
    {
        setTimeout(() =>
        {
            $("#loaderGif").hide();
            $("#loaderGif").addClass('ocultar');
        }, 1500);

        $('#tbl_availability').DataTable({
            'ordering': false
            , "lengthMenu": [
                [10, 25, 50, 100, -1]
                , [10, 25, 50, 100, 'ALL']
            ]
            , dom: 'Blfrtip'
            , "info": "Showing page _PAGE_ de _PAGES_"
            , "infoEmpty": "No hay registros"
            , "buttons": [{
                    extend: 'copyHtml5'
                    , text: 'Copiar'
                    , className: 'waves-effect waves-light btn-rounded btn-sm btn-primary'
                    , init: function(api, node, config) {
                        $(node).removeClass('dt-button')
                    }
                }
                , {
                    extend: 'excelHtml5'
                    , text: 'Excel'
                    , className: 'waves-effect waves-light btn-rounded btn-sm btn-primary'
                    , init: function(api, node, config) {
                        $(node).removeClass('dt-button')
                    }
                }
            , ]
        });
    });

    $('#select_pending').on('change', function()
    {
        if ($('#select_pending').is(':checked'))
        {
            checked = $('#select_pending').is(':checked');

            if (checked == true)
            {
                $('.checke').prop('checked', true);
                $('.btn-pending').hide();

                idEventos = $("input:checkbox[class^='checke']:checked").map(function() {
                    return parseInt($(this).val());
                }).get();
            }

        } else
        {
            $('.checke').prop('checked', false);
            $('.btn-pending').show();
        }
    });

    function actualizacionMasiva(id_estado)
    {
        if(idEventos == [] || idEventos == undefined)
        {
            Swal.fire(
                'Error',
                'Please, select the availabilities that you want to approve, reject or delete',
                'error'
            );

            return;

        } else
        {
            $.ajax({
                async: true,
                url: "{{route('actualizacion_masiva_diponibilidades')}}",
                type: "POST",
                dataType: "JSON",
                data: {
                    "idEstado": id_estado,
                    "idsDisponibilidades": idEventos
                },
                beforeSend: function()
                {
                    $("#loaderGif").show();
                    $("#loaderGif").removeClass('ocultar');
                },
                success: function(response)
                {
                    if(response == "redirect")
                    {
                        $("#loaderGif").hide();
                        $("#loaderGif").addClass('ocultar');
                        window.location.href = `${window.location.hostname}:${window.location.port}`;
                        return;
                    }

                    if(response == "exito")
                    {
                        $("#loaderGif").hide();
                        $("#loaderGif").addClass('ocultar');
                        Swal.fire({
                            position: 'center'
                            , title: 'Success!'
                            , html: 'Availabities updated successfully!'
                            , icon: 'success'
                            , type: 'success'
                            , showCancelButton: false
                            , showConfirmButton: false
                            , timer: 3000
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 3500);
                    }

                    if(response =="error")
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
                            , timer: 3000
                        });

                        return;
                    }

                    if(response =="error_exception")
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
                            , timer: 3000
                        });

                        return;
                    }
                }
            });
        }
    }
</script>
@endsection
