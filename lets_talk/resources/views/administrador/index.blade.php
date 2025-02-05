@extends('layouts.layout')
@section('title', 'Index')
@section('css')
    <link href="{{asset('DataTable/datatables.min.css')}}" rel="stylesheet">
@stop
@section('content')

<div class="row">
    <div class="col-12">
        <h1 class="text-center text-uppercase">User's List</h1>
    </div>
</div>

<div class="row p-b-20 float-right" style="padding-left:5rem;padding-right:5rem;">
    <div class="col-12">
        <a href="{{route('administrador.create')}}" class="btn btn-primary">Create New User</a>
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
                        <th>Document Type</th>
                        <th>Document Number</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>English Level</th>
                        <th>English Type</th>
                        <th>State</th>
                        <th>View Details</th>
                        <th>Edit</th>
                        <th>Change State</th>
                        <th>Update Password</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{$usuario->nombres}}</td>
                            <td>{{$usuario->apellidos}}</td>
                            <td>{{$usuario->usuario}}</td>
                            <td>{{$usuario->tipo_documento}}</td>
                            <td>{{$usuario->numero_documento}}</td>
                            <td>{{$usuario->correo}}</td>

                            <td>{{$usuario->nombre_rol}}</td>

                            @if($usuario->id_rol == 3 || $usuario->id_rol == "3")
                                    <td>
                                        <span class="badge badge-warning">
                                            {{$usuario->niveles}}
                                        </span>
                                    </td>
                                    <td>---</td>
                            @else
                                <td>---</td>
                                <td>
                                        <span class="badge badge-info">
                                            {{$usuario->desc_tip_ing}}
                                        </span>
                                </td>
                            @endif

                            @if($usuario->estado == 1)
                                <td><span class='badge badge-success'>Active</span></td>
                            @else
                                <td><span class='badge badge-danger'>Inactive</span></td>
                            @endif
                            <td>
                                <a href="{{route('administrador.show', $usuario->id_user)}}"
                                    class="btn btn-secondary" title="View Details">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{route('administrador.edit', $usuario->id_user)}}"
                                    class="btn btn-primary" title="Edit">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <input type="hidden" name="id_user"
                                        id="id_user" value="{{$usuario->id_user}}">
                            </td>
                            <td>
                                @if($usuario->id_rol == 2 || $usuario->id_rol == "2")
                                    <a href="#" class="btn btn-warning"
                                        title="Change Status" disabled id="cambiar_estado_{{$usuario->id_user}}">
                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                    </a>
                                @else
                                    <a href="#" class="btn btn-warning" title="Change Status"
                                        id="cambiar_estado_{{$usuario->id_user}}"
                                        onclick="cambiarEstado({{$usuario->id_user}})">
                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-info" title="Update Password"
                                        id="pass_update_{{$usuario->id_user}}"
                                        onclick="updatePassword({{$usuario->id_user}})">
                                    <i class="fa fa-key" aria-hidden="true"></i>
                                </button>
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

       function cambiarEstado(user_id)
       {
            Swal.fire({
                title: 'You really want',
                html: 'to change the status of this user?',
                icon: 'info',
                type: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value)
                {
                    $.ajax({
                        async: true,
                        url: "{{route('cambiar_estado')}}",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            'id_user': user_id
                        },
                        beforeSend: function()
                        {
                            $("#loaderGif").show();
                            $("#loaderGif").removeClass('ocultar');
                        },
                        success: function(response)
                        {
                            if(response == "-1")
                            {
                                $("#loaderGif").hide();
                                $("#loaderGif").addClass('ocultar');
                                Swal.fire({
                                    position: 'center',
                                    title: 'Error!',
                                    html:  'An error occurred, try again, if the problem persists contact support.',
                                    icon: 'info',
                                    type: 'info',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey:false,
                                    timer: 6000
                                });
                                return;
                            }

                            if(response == 0 || response == "0")
                            {
                                $("#loaderGif").hide();
                                $("#loaderGif").addClass('ocultar');
                                Swal.fire({
                                    position: 'center',
                                    title: 'Error!',
                                    html:  'An error occurred, try again, if the problem persists contact support.',
                                    icon: 'info',
                                    type: 'info',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey:false,
                                    timer: 5000
                                });
                                return;
                            }

                            if(response == "success")
                            {
                                $("#loaderGif").hide();
                                $("#loaderGif").addClass('ocultar');
                                Swal.fire({
                                    position: 'center',
                                    title: 'Success!',
                                    html:  "The user's status has been successfully updated",
                                    icon: 'success',
                                    type: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey:false,
                                    timer: 2000
                                });

                                setTimeout(function(){
                                    window.location.reload();
                                }, 3000);
                                return;
                            }
                        }
                    });
                }
            });
        }

        function updatePassword(id_user)
        {
            Swal.fire({
                title: 'Update Password',
                html: '<input class="form-control"' +
                       'placeholder="Entered the new password" type="password" name="change_clave" id="change_clave">',
                icon: 'info',
                type: 'info',
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel',
                cancelButtonClassName: 'color-cancel-button'
            }).then((result) =>
            {
                let new_clave = $("#change_clave").val();

                if(new_clave === '' || new_clave == "" || new_clave == undefined)
                {
                    Swal.fire({
                        position: 'center',
                        title: 'Error!',
                        html:  "The New Password is Required",
                        icon: 'error',
                        type: 'error',
                        showCancelButton: false,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey:false,
                        timer: 2000
                    });

                    return;
                }

                if (result.value)
                {
                    $.ajax({
                        async: true,
                        url: "{{route('actualizar_clave')}}",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            'id_user': id_user,
                            'clave': new_clave
                        },
                        beforeSend: function()
                        {
                            $("#loaderGif").show();
                            $("#loaderGif").removeClass('ocultar');
                        },
                        success: function(response)
                        {
                            if(response == "-1")
                            {
                                $("#loaderGif").hide();
                                $("#loaderGif").addClass('ocultar');
                                Swal.fire({
                                    position: 'center',
                                    title: 'Error!',
                                    html:  'The password is required',
                                    icon: 'error',
                                    type: 'error',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey:false,
                                    timer: 3000
                                });
                                return;
                            }

                            if(response == 0 || response == "0")
                            {
                                $("#loaderGif").hide();
                                $("#loaderGif").addClass('ocultar');
                                Swal.fire({
                                    position: 'center',
                                    title: 'Error!',
                                    html:  'An error occurred, try again, if the problem persists contact support.',
                                    icon: 'info',
                                    type: 'info',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey:false,
                                    timer: 5000
                                });
                                return;
                            }

                            if(response == "success")
                            {
                                $("#loaderGif").hide();
                                $("#loaderGif").addClass('ocultar');
                                Swal.fire({
                                    position: 'center',
                                    title: 'Success!',
                                    html:  "The user's password has been successfully updated",
                                    icon: 'success',
                                    type: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey:false,
                                    timer: 2000
                                });

                                setTimeout(function(){
                                    window.location.reload();
                                }, 3000);

                                return;
                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection
