
 <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="wrap-input100">
            {!! Form::text('zoom', isset($usuario) ? $usuario->zoom : null, ['class' => 'input100', 'id' => 'zoom']) !!}
            <span class="focus-input100" data-placeholder="Zoom"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3">
        <div class="wrap-input100">
            {!! Form::text('zoom_clave', isset($usuario) ? $usuario->zoom_clave : null, ['class' => 'input100', 'id' => 'zoom_clave']) !!}
            <span class="focus-input100" data-placeholder="Zoom Pass"></span>
        </div>
    </div>
</div>

<!-- First Contact -->
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-3" id="div_id_primer_contacto">
        <div class="wrap-input100" id="1_cont">
            {!! Form::select('id_primer_contacto', $tipo_contacto, isset($usuario) ? $usuario->primer_contacto_tipo : null, ['class' => 'input100 select2', 'id' => 'id_primer_contacto']) !!}
            <span class="focus-input100" data-placeholder="First Contact"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_primer_telefono">
        <div class="wrap-input100" id="div_tel_1_cont">
            {!! Form::text('primer_telefono', isset($usuario) ? $usuario->primer_telefono : null, ['class' => 'input100', 'id' => 'primer_telefono']) !!}
            <span class="focus-input100" data-placeholder="Phone"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_primer_celular_whatsapp">
        <div class="wrap-input100" id="div_whats_1_cont">
            {!! Form::text('primer_celular', isset($usuario) ? $usuario->primer_celular : null, ['class' => 'input100', 'id' => 'primer_celular']) !!}
            <span class="focus-input100 text-danger" data-placeholder="Whatsapp"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_primer_correo">
        <div class="wrap-input100" id="div_email_1_cont">
            {!! Form::email('primer_correo', isset($usuario) ? $usuario->primer_correo : null, ['class' => 'input100', 'id' => 'primer_correo']) !!}
            <span class="focus-input100 text-danger" data-placeholder="Email"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_primer_skype">
        <div class="wrap-input100" id="div_skype_1_cont">
            {!! Form::text('primer_skype', isset($usuario) ? $usuario->primer_skype : null, ['class' => 'input100', 'id' => 'primer_skype']) !!}
            <span class="focus-input100" data-placeholder="Skype"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_primer_zoom">
        <div class="wrap-input100" id="div_zoom_1_cont">
            {!! Form::text('primer_zoom', isset($usuario) ? $usuario->primer_zoom : null, ['class' => 'input100', 'id' => 'primer_zoom']) !!}
            <span class="focus-input100" data-placeholder="Zoom"></span>
        </div>
    </div>

    <!-- Second contact -->
    <div class="col-xs-12 col-sm-12 col-md-3" id="div_id_segundo_contacto">
        <div class="wrap-input100">
            {!! Form::select('id_segundo_contacto', $tipo_contacto, isset($usuario) ? $usuario->segundo_contacto_tipo : null, ['class' => 'input100 select2', 'id' => 'id_segundo_contacto']) !!}
            <span class="focus-input100" data-placeholder="Second Contact"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_segundo_telefono">
        <div class="wrap-input100">
            {!! Form::text('segundo_telefono', isset($usuario) ? $usuario->segundo_telefono : null, ['class' => 'input100', 'id' => 'segundo_telefono']) !!}
            <span class="focus-input100" data-placeholder="Phone"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_segundo_celular_whatsapp">
        <div class="wrap-input100">
            {!! Form::text('segundo_celular', isset($usuario) ? $usuario->segundo_celular : null, ['class' => 'input100', 'id' => 'segundo_celular']) !!}
            <span class="focus-input100 text-danger" data-placeholder="Whatsapp"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_segundo_correo">
        <div class="wrap-input100">
            {!! Form::email('segundo_correo', isset($usuario) ? $usuario->segundo_correo : null, ['class' => 'input100', 'id' => 'segundo_correo']) !!}
            <span class="focus-input100 text-danger" data-placeholder="Email"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_segundo_skype">
        <div class="wrap-input100">
            {!! Form::text('segundo_skype', isset($usuario) ? $usuario->segundo_skype : null, ['class' => 'input100', 'id' => 'segundo_skype']) !!}
            <span class="focus-input100" data-placeholder="Skype"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_segundo_zoom">
        <div class="wrap-input100">
            {!! Form::text('segundo_zoom', isset($usuario) ? $usuario->segundo_zoom : null, ['class' => 'input100', 'id' => 'segundo_zoom']) !!}
            <span class="focus-input100" data-placeholder="Zoom"></span>
        </div>
    </div>

    <!-- Optional contact -->
    <div class="col-xs-12 col-sm-12 col-md-3" id="div_id_opcional_contacto">
        <div class="wrap-input100">
            {!! Form::select('id_opcional_contacto', $tipo_contacto, isset($usuario) ? $usuario->opcional_contacto_tipo : null, ['class' => 'input100 select2', 'id' => 'id_opcional_contacto']) !!}
            <span class="focus-input100" data-placeholder="Optional Contact"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_opcional_telefono">
        <div class="wrap-input100">
            {!! Form::text('opcional_telefono', isset($usuario) ? $usuario->opcional_telefono : null, ['class' => 'input100', 'id' => 'opcional_telefono']) !!}
            <span class="focus-input100" data-placeholder="Phone"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_opcional_celular_whatsapp">
        <div class="wrap-input100">
            {!! Form::text('opcional_celular', isset($usuario) ? $usuario->opcional_celular : null, ['class' => 'input100', 'id' => 'opcional_celular']) !!}
            <span class="focus-input100 text-danger" data-placeholder="Whatsapp"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_opcional_correo">
        <div class="wrap-input100">
            {!! Form::email('opcional_correo', isset($usuario) ? $usuario->opcional_correo : null, ['class' => 'input100', 'id' => 'opcional_correo']) !!}
            <span class="focus-input100 text-danger" data-placeholder="Email"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_opcional_skype">
        <div class="wrap-input100">
            {!! Form::text('opcional_skype', isset($usuario) ? $usuario->opcional_skype : null, ['class' => 'input100', 'id' => 'opcional_skype']) !!}
            <span class="focus-input100" data-placeholder="Skype"></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-3 ocultar" id="div_opcional_zoom">
        <div class="wrap-input100">
            {!! Form::text('opcional_zoom', isset($usuario) ? $usuario->opcional_zoom : null, ['class' => 'input100', 'id' => 'opcional_zoom']) !!}
            <span class="focus-input100" data-placeholder="Zoom"></span>
        </div>
    </div>
</div>