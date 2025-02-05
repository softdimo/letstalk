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
        @if($disponibilidad->state == 1 )
            <span class="badge badge-success"
                    style="border-radius: 15px;">{{$disponibilidad->descripcion_estado}}</span>
        @elseif($disponibilidad->state == 2)
            <span class="badge badge-warning" 
                    style="border-radius: 15px; background-color:yellow;">{{$disponibilidad->descripcion_estado}}</span>
        @elseif($disponibilidad->state == 3)
            <span class="badge badge-warning"
                    style="border-radius: 15px;">{{$disponibilidad->descripcion_estado}}</span>
        @elseif($disponibilidad->state == 11)
            <span class="badge badge-info"
                    style="border-radius: 15px;">{{$disponibilidad->descripcion_estado}}</span>
        @else
            <span class="badge badge-danger"
                    style="border-radius: 15px;">{{$disponibilidad->descripcion_estado}}</span>
        @endif
    </td>
    @endif

    @if(session('rol') == 2)
        <td class="d-flex justify-content-center" style="text-align: center; vertical-align:middle;">
            @if($disponibilidad->state == 2)
                <input class="checke" type="checkbox"
                        value="{{$disponibilidad->id}}" name="availability_pending[]" id="availability_pending_{{$disponibilidad->id}}">
            @else
                <span></span>
            @endif
        </td>
    @endif

    <td>
        @if($disponibilidad->state == 1 && session('rol') == 2)
            <a href="#" class="btn btn-sm btn-success ocultar rounded" 
                title="Approve" id="btn_aprove_{{$disponibilidad->id}}" disabled
                onclick="actualizarEstadoEvento(1, {{$disponibilidad->id}})">Approve</a>
        @elseif($disponibilidad->state == 2 && session('rol') == 2)
            <a href="#" class="btn btn-sm btn-success btn-pending"
                title="Approve" id="btn_aprove_{{$disponibilidad->id}}"
                onclick="actualizarEstadoEvento(1, {{$disponibilidad->id}})">Approve</a>
            <a href="#" class="btn btn-sm btn-warning btn-pending"
                title="Reject" id="btn_reject_{{$disponibilidad->id}}"
                onclick="actualizarEstadoEvento(3, {{$disponibilidad->id}})">Reject</a>
            <a href="#" class="btn btn-sm btn-danger btn-pending"
                title="Delete" id="btn_delete_{{$disponibilidad->id}}"
                onclick="actualizarEstadoEvento(4, {{$disponibilidad->id}})">Delete</a>
        @elseif($disponibilidad->state == 3 && session('rol') == 2)
            <a href="#" class="btn btn-sm btn-warning ocultar"
                title="Reject" id="btn_reject_{{$disponibilidad->id}}" disabled
                onclick="actualizarEstadoEvento(3, {{$disponibilidad->id}})">Reject</a>
            <a href="#" class="btn btn-sm btn-danger"
                title="Delete" id="btn_delete_{{$disponibilidad->id}}"
                onclick="actualizarEstadoEvento(4, {{$disponibilidad->id}})">Delete</a>
        @else
            &nbsp;
        @endif

        @if((session('rol') == 3 || session('rol') == "3"))
            <a href="#" class="btn btn-sm btn-info" title="reservation" id="btn_reservation">Realizar Reserva</a>
        @endif
    </td>
</tr>
