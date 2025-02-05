@extends('layouts.layout')
@section('title', 'Leveles')

@section('css')
    <link href="{{asset('DataTable/datatables.min.css')}}" rel="stylesheet">

    <style>
        .div-level-name{
            margin-top: 1rem;
            margin-bottom: 2rem;
        }
        .level-name{
            border: 2px solid lightgray;
            border-radius: 5px;
            width: 70%;
            text-align:center;
            text-transform: uppercase;
            font-size: 14px;
        }
        .div-new-level{
            margin-top: 5rem;
            padding-right: 5rem !important;
        }
        #btn_editar_nivel {
            display: block;
            margin-top: 2rem;
            margin-bottom: 2rem;
            margin-left: auto;
            margin-right: auto;
        }

        .font14{
            font-size: 14px;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="text-center text-uppercase">List Levels</h1>
        </div>
    </div>

    <div class="row div-new-level">
        <div class="col-12">
            <button class="btn btn-primary float-right" onclick="crearNivel()">Create New Level</button>
        </div>
    </div>

    <div class="row p-t-30" style="padding-left:5rem;padding-right:5rem;">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dt-button"
                        id="tbl_levels" aria-describedby="tabla niveles">
                    <thead>
                        <tr class="header-table">
                            <th>ID</th>
                            <th>Level</th>
                            <th>File</th>
                            <th>State</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($niveles as $nivel)
                            <tr>
                                <td>{{$nivel->id_nivel}}</td>
                                <td>
                                    {{$nivel->nivel_descripcion}}
                                    <input type="hidden" value="{{$nivel->id_nivel}}" id="levelId{{$nivel->id_nivel}}" name="levelId{{$nivel->id_nivel}}">
                                    <input type="hidden" value="{{$nivel->nivel_descripcion}}"
                                            id="levelName{{$nivel->id_nivel}}" name="levelName{{$nivel->id_nivel}}">
                                </td>

                                @if ($nivel->ruta_pdf_nivel != null || $nivel->ruta_pdf_nivel != "")
                                    <td>
                                        <a href="/storage/{{$nivel->ruta_pdf_nivel}}" target="_blank">Level File</a>
                                    </td>
                                @else
                                    <td></td>
                                @endif

                                @if ($nivel->deleted_at == null || $nivel->deleted_at == "")
                                    <td>Active</td>
                                @else
                                    <td>Inactive</td>
                                @endif

                                <td>
                                    @if ($nivel->deleted_at == null || $nivel->deleted_at == "")
                                        @if ($nivel->id_nivel == 0)
                                            <span class="badge badge-primary">No Edition Allowed</span>
                                        @else
                                            <button class="btn btn-info"
                                                id="level_update_{{$nivel->id_nivel}}"
                                                onclick="editarNivel({{$nivel->id_nivel}})">Edit Level</button>
                                            <button class="btn btn-warning" id="level_inactive_{{$nivel->id_nivel}}"
                                                onclick="inactivarNivel({{$nivel->id_nivel}})">Inactive Level</button>
                                        @endif
                                    @else
                                        <button class="btn btn-success" id="level_active_{{$nivel->id_nivel}}"
                                            onclick="activarNivel({{$nivel->id_nivel}})">Active Level</button>
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
        $( document ).ready(function()
        {
            setTimeout(() =>
            {
                $("#loaderGif").hide();
                $("#loaderGif").addClass('ocultar');
            }, 1500);

            $('#tbl_levels').DataTable({
                'ordering': false,
                "lengthMenu": [[10,25,50,100, -1], [10,25,50,100, 'ALL']],
                dom: 'Blfrtip',
                "info": "Showing page _PAGE_ de _PAGES_",
                "infoEmpty": "No registers",
                "buttons": [
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
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

        function crearNivel()
        {
            html = ``;
            html +=     `<label class="font14">This option creates a new level</label>`;
            html +=     `<div class="div-level-name">
                            <label for="crear_nivel">Level Name</label>
                            <input type="text" name="crear_nivel" id="crear_nivel" class="level-name" required/>
                        </div>
            `;
            html += `
                    <div class="alert alert-danger ocultar" role="alert" id="level_alert">
                      The field Level Name is required
                    </div>
            `;

            html += `       <div class="file-container" style="margin-top:5rem">
                                <div class="div-file">
                                    <input type="file" name="file_crear_nivel" id="file_crear_nivel" class="file"
                                            onchange="validationsSelectedFile()" />
                                </div>
                            </div>
                            <br>
            `;

            html += `
                        <br>
                        <div class="alert alert-danger ocultar" role="alert" id="alertFile">
                            <span id="msgAlert"></span>
                        </div>
            `;

            html += `
                        <br>
                        <div class="alert alert-danger ocultar" role="alert" id="alertFileSize">
                            The file weight must not exceed 10 mb.
                        </div>
            `;

            html += `<img  class="ocultar" src="{{asset('img/loading.gif')}}"
                            id="loading_ajax"
                            alt="loading..." />
            `;

            html += `<div class="div-level-name">
                            <input type="button" value="Create Level" class="btn btn-primary" id="btn_crear_nivel">
                        </div>
            `;

            Swal.fire({
                title: 'Create Level',
                html: html,
                icon: 'success',
                type: 'success',
                showConfirmButton: false,
                focusConfirm: false,
                showCloseButton: true,
                showCancelButton: false,
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
            });

            $('#btn_crear_nivel').on('click', function ()
            {
                let nuevoNivel = $('#crear_nivel').val();

                if (nuevoNivel == "" || nuevoNivel == undefined)
                {
                    $('#crear_nivel').attr('required', true);
                    $("#level_alert").show();
                    $("#level_alert").removeClass('ocultar');
                } else
                {
                    $('#btn_crear_nivel').attr('disabled',true);
                    $("#level_alert").hide();
                    $("#level_alert").addClass('ocultar');

                    var formData = new FormData();
                    var fileData = $("#file_crear_nivel").prop("files")[0];

                    formData.append("file_crear_nivel", fileData);
                    formData.append("nuevo_crear_nivel", nuevoNivel);

                    $.ajax({
                        cache: false,
                        contentType: false,
                        async: true,
                        url: "{{route('crear_nivel')}}",
                        type: "POST",
                        dataType: "JSON",
                        enctype: 'multipart/form-data',
                        processData: false,
                        data: formData,
                        beforeSend: function ()
                        {
                            $("#loading_ajax").show();
                            $("#loading_ajax").removeClass('ocultar');
                        },
                        success: function (respuesta)
                        {
                            $("#loading_ajax").hide();
                            $("#loading_ajax").addClass('ocultar');

                            if(respuesta == "to_home")
                            {
                                $("#loading_ajax").hide();
                                $("#loading_ajax").addClass('ocultar');

                                window.location.href = `${window.location.hostname}:${window.location.port}`;
                                return;
                            }
                            
                            if (respuesta == "nivel_creado")
                            {
                                $("#loading_ajax").hide();
                                $("#loading_ajax").addClass('ocultar');

                                Swal.fire(
                                    'Great!',
                                    'New level has been created successfully!',
                                    'success'
                                );

                                window.location.reload();
                                return;
                            }

                            if (respuesta == "nivel_existe")
                            {
                                $("#loading_ajax").hide();
                                $("#loading_ajax").addClass('ocultar');

                                Swal.fire(
                                    'Warning!',
                                    'This level name already exists!',
                                    'warning'
                                );
                                return;
                            }

                            if (respuesta == "error_exception")
                            {
                                $("#loading_ajax").hide();
                                $("#loading_ajax").addClass('ocultar');

                                Swal.fire(
                                    'Wrong!',
                                    'An error has ocurred, please contact support!',
                                    'error'
                                );
                                return;
                            }
                        }
                    })
                }
            });

            setInterval(() => {
                $("#level_alert").hide();
                $("#level_alert").addClass('ocultar');
            }, 6000);
        }

        function editarNivel(idNivel)
        {
            let nameLevel = $("#levelName"+idNivel).val();
            let idLevel = $("#levelId"+idNivel).val();
            
            let html = "";
            html += `{!! Form::open(['method' => 'POST', 'route' => ['editar_nivel'],
                                'class'=>['form-horizontal form-bordered'], 'enctype' => 'multipart/form-data',
                                'id'=>'form_edit_nivel', 'autocomplet'=>'off']) !!}`;
            html += `@csrf`;
            html +=     `<input type="hidden" name="id_nivel" id="id_nivel" value="${idNivel}" required />`;
            html +=     `<label class="font14">Enter the new level name</label>`;
            html +=     `<div class="div-level-name">
                            <input type="text" name="editar_nivel" id="editar_nivel"
                                    class="level-name" value="${nameLevel}" autocomplete="off" required />
                        </div>
            `;
            html += `
                        <div class="alert alert-danger ocultar" role="alert" id="alert_edit">
                            The field Level Name is required
                        </div>
                    `;

            html += `   <div class="file-container" style="margin-top:5rem">
                            <div class="div-file">
                                <input type="file" name="file_editar_nivel" id="file_editar_nivel" class="file"
                                     onchange="validationsSelectedFile()" />
                            </div>
                            <p style="color: red;">If you want to replace the file, select a new file and the system will update it</p>
                        </div>
            `;

            html += `
                        <br>
                        <div class="alert alert-danger ocultar" role="alert" id="alertFileEdit">
                            <span id="msgAlertEditar"></span>
                        </div>
            `;

            html += `
                        <br>
                        <div class="alert alert-danger ocultar" role="alert" id="alertFileSizeEdit">
                            The file weight must not exceed 10 mb.
                        </div>
            `;

            html += `{!! Form::close() !!}`;

            Swal.fire({
                title: 'Update Level',
                html: html,
                icon: 'info',
                type: 'info',
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) =>
            {
                if(result.value) {
                    
                    let editarNivel = $('#editar_nivel').val();
                    let formulario = $('#form_edit_nivel');
    
                    if (editarNivel == "" || editarNivel == undefined)
                    {
                        $('#editar_nivel').attr('required', true);
                        
                        Swal.fire(
                            'Wrong!',
                            'The field Level Name is required',
                            'error'
                        );
                        return;
    
                    } else
                    {
                        $("#loaderGif").show();
                        $("#loaderGif").removeClass('ocultar');
                        
                        formulario.submit();
                    }
                }
            });
        }

        function inactivarNivel(idNivel)
        {
            html = ``;
            html += `{!! Form::open(['method' => 'POST', 'route' => ['inactivar_nivel'],
                                'class'=>['form-horizontal form-bordered'], 'id'=>'form_inactivar_nivel']) !!}`;
            html += `@csrf`;
            html +=     `<input type="hidden" name="id_nivel" id="id_nivel" value="${idNivel}" required />`;
            html +=     `<label class="font14">This option inactive this level, ¿Are you sure?</label>`;
            html += `<img  class="ocultar" src="{{asset('img/loading.gif')}}" id="loading_ajax" alt="loading..." />`;
            html +=     `<div class="div-level-name">
                            <input type="button" value="Yes, inactivate"
                                    class="btn btn-primary" id="btn_inactivar_nivel">
                        </div>
            `;
            html += `{!! Form::close() !!}`;

            Swal.fire({
                title: 'Inactive Level',
                html: html,
                icon: 'warning',
                type: 'warning',
                showConfirmButton: false,
                focusConfirm: false,
                showCloseButton: true,
                showCancelButton: false,
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
            });

            $("#btn_inactivar_nivel").click(function()
            {
                $("#loading_ajax").show();
                $("#loading_ajax").removeClass('ocultar');
                $("#btn_inactivar_nivel").attr('disabled', true);
                $("#form_inactivar_nivel").submit();
            });
        }

        function activarNivel(idNivel)
        {
            html = ``;
            html += `{!! Form::open(['method' => 'POST', 'route' => ['activar_nivel'],
                            'class'=>['form-horizontal form-bordered'], 'id'=>'form_activar_nivel']) !!}`;
            html += `@csrf`;
            html +=     `<input type="hidden" name="id_nivel" id="id_nivel" value="${idNivel}" required />`;
            html +=     `<label class="font14">This option active this level, ¿Are you sure?</label>`;
            html += `<img  class="ocultar" src="{{asset('img/loading.gif')}}" id="loading_ajax" alt="loading..." />`;
            html +=     `<div class="div-level-name">
                            <input type="button" value="Yes, activate" class="btn btn-primary" id="btn_activar_nivel">
                        </div>
            `;
            html += `{!! Form::close() !!}`;

            Swal.fire({
                title: 'Active Level',
                html: html,
                icon: 'success',
                type: 'success',
                showConfirmButton: false,
                focusConfirm: false,
                showCloseButton: true,
                showCancelButton: false,
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
            });

            $("#btn_activar_nivel").click(function()
            {
                $("#loading_ajax").show();
                $("#loading_ajax").removeClass('ocultar');
                $("#btn_activar_nivel").attr('disabled', true);
                $("#form_activar_nivel").submit();
            });
        }

        function validationsSelectedFile()
        {
            // validacion extensiones archivo seleccionado
            let ruta = $('#file_crear_nivel').val();
            let rutaEditar = $('#file_editar_nivel').val();
            let extension = undefined;
            let extensionEditar = undefined;
            let fileSize = undefined;
            let fileSizeEditar = undefined;
            
            if(ruta !== undefined || ruta != undefined)
            {
                extension = ruta.split('.').pop().toLowerCase();
            }

            if(rutaEditar !== undefined || rutaEditar != undefined)
            {
                extensionEditar = rutaEditar.split('.').pop().toLowerCase();
            }

            let allowedFiles = ['jpeg','jpg','png','pdf'];

            if((extension !== undefined && jQuery.inArray(extension, allowedFiles) !== -1) ||
                (extensionEditar !== undefined && jQuery.inArray(extensionEditar, allowedFiles) !== -1))
            {
                $("#alertFile").hide('slow');
                $("#alertFileEdit").hide('slow');
                $("#alertFile").addClass('ocultar');
                $("#alertFileEdit").addClass('ocultar');

                $("#alertFileSize").hide('slow');
                $("#alertFileSizeEdit").hide('slow');
                $("#alertFileSize").addClass('ocultar');
                $("#alertFileSizeEdit").addClass('ocultar');
            } else
            {
                $("#alertFile").show('slow');
                $("#alertFileEdit").show('slow');
                $("#alertFile").removeClass('ocultar');
                $("#alertFileEdit").removeClass('ocultar');
                
                $("#alertFileSize").hide('slow');
                $("#alertFileSizeEdit").hide('slow');
                $("#alertFileSize").addClass('ocultar');
                $("#alertFileSizeEdit").addClass('ocultar');

                $("#msgAlert").empty();
                $("#msgAlertEditar").empty();
                $("#msgAlert").append('The file must be extension: ' + allowedFiles);
                $("#msgAlertEditar").append('The file must be extension: ' + allowedFiles);
                $('#file_crear_nivel').val('');
                $('#file_editar_nivel').val('');
            }

            // validacion peso del archivo seleccionado
            const maxSize = 10 * 1024 * 1024; // 10 mb

            if(ruta !== undefined || ruta != undefined)
            {
                fileSize = $('#file_crear_nivel')[0].files[0].size;
            }

            if(rutaEditar !== undefined || rutaEditar != undefined)
            {
                fileSizeEditar = $('#file_editar_nivel')[0].files[0].size;
            }
            
            if((fileSize !== undefined && fileSize > maxSize) ||
              (fileSizeEditar !== undefined && fileSizeEditar > maxSize))
            {
                $("#alertFile").hide('slow');
                $("#alertFileEditar").hide('slow');
                $("#alertFile").addClass('ocultar');
                $("#alertFileEditar").addClass('ocultar');
                $("#alertFileSize").show('slow');
                $("#alertFileSizeEdiotar").show('slow');
                $("#alertFileSize").removeClass('ocultar');
                $("#alertFileSizeEditar").removeClass('ocultar');
                $('#file_crear_nivel').val('');
                $('#file_editar_nivel').val('');
            } else
            {
                $("#alertFile").hide('slow');
                $("#alertFileEditar").hide('slow');
                $("#alertFile").addClass('ocultar');
                $("#alertFileEditar").addClass('ocultar');
                $("#alertFileSize").hide('slow');
                $("#alertFileSizeEditar").hide('slow');
                $("#alertFileSize").addClass('ocultar');
                $("#alertFileSizeEditar").addClass('ocultar');
            }
        }
    </script>
@endsection
