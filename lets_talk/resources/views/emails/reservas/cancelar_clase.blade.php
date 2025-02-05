<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Clase Rerservada</title>
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
    <p>Hola! <b>{{$instructor->nombres}} {{$instructor->apellidos}}</b>,</p>
    <p>El/la estudiante <b>{{$estudiante->nombres}} {{$estudiante->apellidos}}</b> ha cancelado la clase programada para este horario:</p>

    <ul>
        <li><b>Fecha:</b> {{$eventoAgendaEntrenador->start_date}}</li>
        <li><b>Hora:</b> {{$eventoAgendaEntrenador->start_time}}-{{$eventoAgendaEntrenador->end_time}}</li>
    </ul>

    <p>Este mensaje es automático, por favor, no responder.</p>
    <p>Muchas Gracias.</p>
    <p><b>&copy; Let`s Talk</b></p>
</body>
</html>
