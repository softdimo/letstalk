{{-- Inicio Menu --}}
<div class="col-xs-6 col-sm-6 col-md-6">
    <div class="sign-out">
        {{-- Rol Entrenador (id = 1) --}}
        @if(!is_null(session('rol')) && (session('rol') == 1 || session('rol') == "1"))
            @include('layouts.menu_entrenador')
        {{-- Rol Estudiante (id = 3) --}}
        @elseif(!is_null(session('rol')) && (session('rol') == 3 || session('rol') == "3"))
            @include('layouts.menu_estudiante')
        @else
        {{-- Rol admin (id = 2) --}}
            @if(!is_null(session('rol')) && (session('rol') == 2 || session('rol') == "2"))
                @include('layouts.menu_admin')
            @else
                <p>&nbsp;</p>
            @endif
        @endif
    </div>
</div>
{{-- Fin Menu --}}
