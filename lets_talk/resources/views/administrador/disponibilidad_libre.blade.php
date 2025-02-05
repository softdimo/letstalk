@extends('layouts.layout')
@section('title', 'Index')
@section('css')
    <link href="{{asset('DataTable/datatables.min.css')}}" rel="stylesheet">
@stop
@section('content')

<div class="row">
    <div class="col-12">
        <h1 class="text-center text-uppercase">FREE TRAINER'S AVAILABILITY</h1>
    </div>
</div>

<div class="row p-t-30" style="padding-left:5rem;padding-right:5rem;">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dt-button"
                    id="tbl_users" aria-describedby="tabla usuarios">
                <thead>
                    <tr class="header-table">
                        <th>Name</th>
                        <th>Lastname</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>English Type</th>
                        <th>Start Date</th>
                        <th>Start Time</th>
                        <th>State Class</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($disponibilidadesLibres as $disponibilidadLibre)
                        <tr>
                            <td>{{$disponibilidadLibre->nombres}}</td>
                            <td>{{$disponibilidadLibre->apellidos}}</td>
                            <td>{{$disponibilidadLibre->usuario}}</td>
                            <td>{{$disponibilidadLibre->nombre_rol}}</td>
                            <td>{{$disponibilidadLibre->tipo_ingles}}</td>
                            <td>{{$disponibilidadLibre->start_date}}</td>
                            <td>{{$disponibilidadLibre->start_time}}</td>
                            <td>{{$disponibilidadLibre->descripcion_estado}}</td>
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
        $( document ).ready(function()
        {
            setTimeout(() => {
                $("#loaderGif").hide();
                $("#loaderGif").addClass('ocultar');
            }, 1500);

            $('#tbl_users').DataTable({
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
    </script>
@endsection
