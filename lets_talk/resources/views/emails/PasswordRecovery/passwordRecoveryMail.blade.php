<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Password Recovery</title>
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
    <p>Hello! <b>{{$usuarioRecovery}}</b>,</p>
    <p>The email <b>{{$correoRecovery}}</b> requested the password recovery</p>
    <p>
        <a href="{{route('recovery_password_link',$idUserRecovery)}}" class="btn btn_disponibilidad">Change Pass</a>
    </p>

    <p>This message is automatic, please don´t reply</p>
    <p>Thank you.</p>
    <p><b>&copy; Let`s Talk</b></p>
</body>
</html>
