{{-- Rol Administrador (id = 2) --}}
<ul class="nav nav-tabs" style="margin-bottom: 0vw !important;">
    <li role="presentation">
        <a class="pointer" href="{{route('administrador.index')}}">Home</a>
    </li>

    <li role="presentation">
        <a href="{{route('trainer.create')}}">Trainer's Agenda</a>
    </li>

    <li role="presentation">
        <a href="{{route('trainer.index')}}">Trainer's Sessions</a>
    </li>

    <li role="presentation">
        <a href="{{route('administrador.disponibilidad_entrenadores')}}">Trainer's Availability </a>
    </li>

    <li role="presentation">
        <a href="{{route('administrador.disponibilidad_admin')}}">Trainer's Schedule</a>
    </li>

    <li role="presentation">
        <a href="{{route('administrador.disponibilidades_libres')}}">Free Availability</a>
    </li>

    <li role="presentation">
        <a href="{{route('administrador.niveles_index')}}">Levels</a>
    </li>

    <li>
        <a href="{{route('logout')}}" title="Cerrar Sesión">
            <i class="fa fa-sign-out fa-3x" aria-hidden="true"></i>
        </a>
    </li>
</ul>
