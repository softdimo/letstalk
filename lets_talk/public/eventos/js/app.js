
let calendarEl = document.getElementById('calendar');
let frm = document.getElementById('formulario');
let eliminar = document.getElementById('btnEliminar');
let myModal = new bootstrap.Modal(document.getElementById('myModal'));
let min = '06:00:00';
let max = '22:00:00';

document.addEventListener('DOMContentLoaded', function ()
{
    calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'local',
        initialView: 'dayGridMonth',
        locale: 'en',
        headerToolbar: {
            left: 'prev next today',
            center: 'title',
            right: 'dayGridMonth listWeek'
        },
        events: [],
        editable: true,
        dateClick: function (info)
        {
            let hoy = moment().format('YYYY-MM-DD');
            let fechaEvento = moment(info.dateStr).format('YYYY-MM-DD');

            if (hoy <= fechaEvento)
            {
                frm.reset();
                eliminar.classList.add('d-none');
                document.getElementById('start').value = info.dateStr;
                document.getElementById('end').value = info.dateStr;
                document.getElementById('id').value = '';
                document.getElementById('btnAccion').textContent = 'Save';
                document.getElementById('titulo').textContent = 'Register Event';
                myModal.show();
            }
            else
            {
                Swal.fire(
                    'Error',
                    'You cannot create or modify events in the past',
                    'error'
                )
                return false;
            }
        },

        eventClick: function (info)
        {
            document.getElementById('id').value = info.event.id;
            document.getElementById('title').value = info.event.title;
            document.getElementById('start').value = info.event.startStr;
            document.getElementById('color').value = info.event.backgroundColor;
            document.getElementById('btnAccion').textContent = 'Modify';
            document.getElementById('titulo').textContent = 'Update Event';
            eliminar.classList.remove('d-none');
            myModal.show();
        },
        eventDrop: function (info)
        {
            const start = info.event.startStr;
            const id = info.event.id;
            const url = base_url + 'Home/drag';
            const http = new XMLHttpRequest();
            const formDta = new FormData();
            formDta.append('start', start);
            formDta.append('id', id);
            http.open("POST", url, true);
            http.send(formDta);
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    const res = JSON.parse(this.responseText);
                     Swal.fire(
                         'Avisos?',
                         res.msg,
                         res.tipo
                     )
                    if (res.estado) {
                        myModal.hide();
                        calendar.refetchEvents();
                    }
                }
            }
        }
    });

    calendar.render();
    frm.addEventListener('submit', function (e)
    {
        e.preventDefault();
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        const start = document.getElementById('start').value;
        const start_time = document.getElementById('start_time').value;
        const end = document.getElementById('end').value;
        const end_time = document.getElementById('end_time').value;
        const color = document.getElementById('color').value;

        if (title == '' || start == '' || start_time == '' ||
            end == '' || end_time == '')
        {
             Swal.fire(
                 'Error',
                 'The fields are required',
                 'error'
             )
        } else
        {
            const url = "{{route('trainer.store')}}";
            const http = new xmlhttprequest();
            http.open("post", url, true);
            http.send(new formdata(frm));
            http.onreadystatechange = function ()
            {
                if (this.readystate == 4 && this.status == 200)
                {
                    console.log(this.responsetext);

                    const res = json.parse(this.responsetext);
                     swal.fire(
                         'error',
                         res.msg,
                         res.tipo
                     )

                    if (res.estado)
                    {
                        mymodal.hide();
                        calendar.refetchevents();
                    }
                }
            }
        }
    });
    eliminar.addEventListener('click', function () {
        myModal.hide();
        Swal.fire({
            title: 'Advertencia?',
            text: "Esta seguro de eliminar!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = base_url + 'Home/eliminar/' + document.getElementById('id').value;
                const http = new XMLHttpRequest();
                http.open("GET", url, true);
                http.send();
                http.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        const res = JSON.parse(this.responseText);
                        Swal.fire(
                            'Avisos?',
                            res.msg,
                            res.tipo
                        )
                        if (res.estado) {
                            calendar.refetchEvents();
                        }
                    }
                }
            }
        })
    });
});

calendar.checkTime = function (calEvent)
{
    if ( calEvent.end.isBefore(Date.now(), 'day') ) {
        swal({
            title: 'Error!',
            text: "You cannot create or modify events in the past.",
            type: 'error'
        });
        return false;
    }
    if (calEvent.start.toDate().getHours() < min.split(':')[0] ||
        calEvent.end.toDate().getHours() > max.split(':')[0]) {
        swal({
            title: 'Error!',
            text: "Events Cannot Be Created At This Time",
            type: 'error'
        });
        return false;
    }
    return true;
}
