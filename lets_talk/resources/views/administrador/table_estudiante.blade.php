<tr>
    <td>{{$disponibilidad->id}}</td>
    <td>{{$disponibilidad->title}}</td>
    <td>{{$disponibilidad->start_date}}</td>
    <td>{{$disponibilidad->start_time}}</td>
    <td>{{$disponibilidad->end_date}}</td>
    <td>{{$disponibilidad->end_time}}</td>
    <td>{{$disponibilidad->nombres}} {{$disponibilidad->apellidos}}</td>
    @if(session('rol') == 2)
        <td>
            @if($disponibilidad->state == 1)
                <span class="badge badge-success">{{$disponibilidad->descripcion_estado}}</span>
            @elseif($disponibilidad->state == 2)
                <span class="badge badge-warning">{{$disponibilidad->descripcion_estado}}</span>
            @elseif($disponibilidad->state == 3)
                <span class="badge badge-info">{{$disponibilidad->descripcion_estado}}</span>
            @else
                <span class="badge badge-danger">{{$disponibilidad->descripcion_estado}}</span>
            @endif
        </td>
    @endif
    <td>
        @if($disponibilidad->state == 1 && session('rol') == 2)
            <a href="" class="btn btn-sm btn-success" title="Approve" id="btn_aprove" disabled>Approve</a>
        @elseif($disponibilidad->state == 2 && session('rol') == 2)
            <a href="" class="btn btn-sm btn-success" title="Approve" id="btn_aprove">Approve</a>
            <a href="" class="btn btn-sm btn-warning" title="Reject" id="btn_reject">Reject</a>
            <a href="" class="btn btn-sm btn-danger" title="Delete" id="btn_delete">Delete</a>
        @elseif($disponibilidad->state == 3 && session('rol') == 2)
            <a href="" class="btn btn-sm btn-warning" title="Reject" id="btn_reject" disabled>Reject</a>
            <a href="" class="btn btn-sm btn-danger" title="Delete" id="btn_delete">Delete</a>
        @else
            &nbsp;
        @endif

        {{-- Si rol es estudiante --}}
        @if(session('rol') == 3)
            <a href="" class="btn btn-sm btn-info" title="reservation" id="btn_reservation">Realizar Reserva</a>
        @endif
    </td>
</tr>
