<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Solicitud Disponibilidad</title>
    <style>
        .btn_disponibilidad
        {
            width: 120px !important;
            background-color: #21277B !important;
            color: #FFFFFF !important;
            font-size: 20px !important;
            text-decoration: none !important;
        }

        .btn
        {
            display:inline-block;
            padding:6px 12px;
            margin-bottom:0;
            font-size:14px;
            font-weight:400;
            line-height:1.42857143;
            text-align:center;
            white-space:nowrap;
            vertical-align:middle;
            -ms-touch-action:manipulation;
            touch-action:manipulation;
            cursor:pointer;
            -webkit-user-select:none;
            -moz-user-select:none;
            -ms-user-select:none;
            user-select:none;
            background-image:none;
            border:1px solid transparent;
            border-radius:4px;
        }
    </style>
</head>
<body>
    <p>Hola! <b>{{$info_admin->nombres}} {{$info_admin->apellidos}}</b>,</p>
    <p>El entrenador <b>{{$info_usuario->nombres}} {{$info_usuario->apellidos}}</b> solicita aprobación para las siguientes disponibilidades:</p>
    @foreach ($disponibilidades as $disponibilidad)
        <ul>
            <li><b>Fecha:</b> {{$disponibilidad->start_date}}</li>
            <li><b>Hora:</b> {{$disponibilidad->start_time}}-{{$disponibilidad->end_time}}</li>
        </ul>
    @endforeach

    <p>
        <a href="{{route('administrador.disponibilidad_entrenadores')}}" class="btn btn_disponibilidad">Ver Solicitud</a>
    </p>

    <p>Este mensaje es automático, por favor, no responder.</p>
    <p>Muchas Gracias.</p>
    <p><b>&copy; Let`s Talk</b></p>
</body>
</html>
