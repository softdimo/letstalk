@extends('layouts.layout')
@section('title', 'Mis Créditos')
@section('css')
    <link href="{{asset('DataTable/datatables.min.css')}}" rel="stylesheet">
    <style>
        .swal2-cancel {
            background-color: #1D9BF0;
            padding: 1rem !important;
            color: #FFF !important;
            box-shadow: 0px 4px 4px 0px #00000040;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h2 class="text-center text-uppercase">Mis créditos</h2>
        </div>
    </div>

    <div class="row p-b-20 float-left" style="padding-left:3rem;padding-right:5rem;">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <a href="{{route('estudiante.index')}}" class="btn btn-primary text-uppercase">Atrás Disponibilidad</a>
        </div>
    </div>

    <div class="row p-t-30" style="padding-left:5rem;padding-right:5rem;">
        <div class="row border w-100 mt-5 mb-5">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="tbl_mis_creditos">
                    <thead>
                        <tr class="header-table">
                            <th>Fecha Compra Paquete</th>
                            <th>Número Paquete</th>
                            <th>Créditos Paquete</th>
                            <th>Créditos Consumidos</th>
                            <th>Créditos Disponibles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($misCreditos as $credito)
                            <tr>
                                <td>{{$credito->fecha_credito}}</td>
                                <td>{{$credito->paquete}}</td>
                                <td>{{$credito->cantidad_total_paquete}}</td>
                                <td>{{$credito->cantidad_consumida}}</td>
                                <td>{{$credito->cantidad_disponible}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="display:flex; justify-content:center;">
                <span class="badge badge-success">Total Créditos: <span class="text-white">{!!$totalCreditosDisponibles!!}</span></span>
            </div>
        </div>
    </div>

    <div class="d-flexswal m-t-30 m-b-30" style="padding-left:3rem;padding-right:5rem;">
        <button type="button" class="btn btn-primary text-uppercase" onclick="misCreditos()">Historial Compras Créditos</button>
    </div>

    <div class="m-t-30 m-b-30" style="padding-left:3rem;padding-right:5rem;">
        <button type="button" class="btn btn-primary text-uppercase" onclick="comprarCreditos()">comprar créditos</button>
    </div>

    @include('layouts.loader')
@stop

@section('scripts')
    <script src="{{asset('DataTable/datatables.min.js')}}"></script>

    <script>
        $(document ).ready(function()
        {
            setTimeout(() => {
                $("#loaderGif").hide();
                $("#loaderGif").addClass('ocultar');
            }, 1500);

            $('#tbl_mis_creditos').DataTable({
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

        let misSesiones = @json($misSesiones);

        function misCreditos() {
            html = ``;
            html += `<table border=1 style="border-collapse:separate !important; width:100%" cellspacing="10" id="tbl_historial_creditos" >`;
            html +=     `<thead>`;
            html +=         `<tr style="background-color: #21277B">`;
            html +=             `<th style="text-align:center;width:55%;color:white;font-size:14px;">RESERVA CON</th>`;
            html +=             `<th style="text-align:center;width:30%;color:white;font-size:14px;">FECHA</th>`;
            html +=             `<th style="text-align:center;width:15%;color:white;font-size:14px;">HORA</th>`;
            html +=         `</tr>`;
            html +=     `</thead>`;
            html +=     `<body>`;
                            misSesiones.forEach(sesion => {
                                let nombre_instructor = sesion.nombre_instructor;
                                let start_date = sesion.start_date;
                                let start_time = sesion.start_time;

                                html += `<tr>`;
                                html +=     `<td style="width:55%;font-size:12px;">${nombre_instructor}</td>`;
                                html +=     `<td style="width:30%;font-size:12px;">${start_date}</td>`;
                                html +=     `<td style="width:15%;font-size:12px;">${start_time}</td>`;
                                html += `</tr>`;
                            });
            html +=     `</body>`;
            html += `<table>`;

            Swal.fire({
                html: html,
                showCloseButton: false,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'GET ME BACK',
                focusConfirm: false,
                allowOutsideClick: false,
                width: 500,
                padding: '3em',
                background: '#fff',
                buttonsStyling: false,
                buttons:{
                    cancelButton: {customClass:'swal2-cancel'}
                }
            });

            // =====================================

            $('#tbl_historial_creditos').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : false,
                'responsive'  : true,
            });
        }

        let paquetes = @json($paquetes);
        
        function comprarCreditos() {
            html = '';
            html += `   <h3 class="gral-font margin-y">Comprar Créditos</h3>`;
            html += `   {!! Form::open(['method' => 'POST', 'route' => ['estudiante.comprar_creditos'],'class'=>['form-horizontal form-bordered']]) !!}`;
            html += `   @csrf`;
            html += `
                    <div class="col-12">
                        <div class="form-group d-flex align-items-center">
                            <select name="cantidad_creditos" class="form-control select2 w-100" id="cantidad_creditos">
                                <option value="" selected >Seleccionar...</option>
            `;
                                $.each(paquetes, function(id_paquete, nombre_paquete){
                                    html += ' <option value="'+id_paquete+'">'+nombre_paquete+'</option>'
                                });

            html += `
                            </select>

                            <div class="alert alert-danger ocultar" role="alert" id="alert_cantCred">
                                Por favor, seleccione una opción
                            </div>
                        </div>
                    </div>
            `;

            html += `       <div class="p-3">
                                <button type="submit" class="text-white btn btn-sm btn-success" id="comprar_creditos">Comprar Créditos</button>
                            </div>
            `;
            html += `   {!! Form::close() !!}`;

            Swal.fire({
                html: html,
                showCloseButton: false,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Regresar',
                focusConfirm: false,
                allowOutsideClick: false,
                width: 500,
                padding: '3em',
                background: '#fff',
                buttonsStyling: false,
                buttons:{
                    cancelButton: {customClass:'swal2-cancel'}
                }
            });
        }

    </script>
@endsection
