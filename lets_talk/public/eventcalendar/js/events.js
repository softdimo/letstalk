mobiscroll.setOptions({
    locale: mobiscroll.localeEs,
    theme: 'ios',
    themeVariant: 'light'
});

$(function () {
    var oldEvent,
        tempEvent = {},
        deleteEvent,
        restoreEvent,
        colorPicker,
        tempColor,
        $title = $('#event-title'),
        $description = $('#event-desc'),
        $allDay = $('#event-all-day'),
        $statusFree = $('#event-status-free'),
        $statusBusy = $('#event-status-busy'),
        $deleteButton = $('#event-delete'),
        $color = $('#event-color'),
        datePickerResponsive = {
            medium: {
                controls: ['calendar'],
                touchUi: false
            }
        },
        datetimePickerResponsive = {
            medium: {
                controls: ['calendar', 'time'],
                touchUi: false
            }
        },
        now = new Date(),
        myData = [{
            id: 1,
            start: '2022-06-08T13:00',
            end: '2022-06-08T13:45',
            title: 'Lunch @ Butcher\'s',
            description: '',
            allDay: false,
            free: true,
            color: '#009788'
        }, {
            id: 2,
            start: '2022-06-14T15:00',
            end: '2022-06-14T16:00',
            title: 'General orientation',
            description: '',
            allDay: false,
            free: false,
            color: '#ff9900'
        }, {
            id: 3,
            start: '2022-06-13T18:00',
            end: '2022-06-13T22:00',
            title: 'Dexter BD',
            description: '',
            allDay: false,
            free: true,
            color: '#3f51b5'
        }, {
            id: 4,
            start: '2022-06-15T10:30',
            end: '2022-06-15T11:30',
            title: 'Stakeholder mtg.',
            description: '',
            allDay: false,
            free: false,
            color: '#f44437'
        }];

    function createAddPopup(elm) {
        // hide delete button inside add popup
        $deleteButton.hide();

        deleteEvent = true;
        restoreEvent = false;

        // set popup header text and buttons for adding
        popup.setOptions({
            headerText: 'New event',
            buttons: ['cancel', {
                text: 'Add',
                keyCode: 'enter',
                handler: function () {
                    calendar.updateEvent({
                        id: tempEvent.id,
                        title: tempEvent.title,
                        description: tempEvent.description,
                        allDay: tempEvent.allDay,
                        start: tempEvent.start,
                        end: tempEvent.end,
                        color: tempEvent.color,
                    });

                    // navigate the calendar to the correct view
                    calendar.navigate(tempEvent.start);

                    deleteEvent = false;
                    popup.close();
                },
                cssClass: 'mbsc-popup-button-primary'
            }]
        });

        // fill popup with a new event data
        $title.mobiscroll('getInst').value = tempEvent.title;
        $description.mobiscroll('getInst').value = '';
        $allDay.mobiscroll('getInst').checked = true;
        range.setVal([tempEvent.start, tempEvent.end]);
        $statusBusy.mobiscroll('getInst').checked = true;
        range.setOptions({ controls: ['date'], responsive: datePickerResponsive });
        selectColor('', true);

        // set anchor for the popup
        popup.setOptions({ anchor: elm });

        popup.open();
    }

    function createEditPopup(args) {
        var ev = args.event;
        // show delete button inside edit popup
        $deleteButton.show();

        deleteEvent = false;
        restoreEvent = true;

        // set popup header text and buttons for editing
        popup.setOptions({
            headerText: 'Edit event',
            buttons: ['cancel', {
                text: 'Save',
                keyCode: 'enter',
                handler: function () {
                    var date = range.getVal();

                    // update event with the new properties on save button click
                    calendar.updateEvent({
                        id: ev.id,
                        title: $title.val(),
                        description: $description.val(),
                        allDay: $allDay.mobiscroll('getInst').checked,
                        start: date[0],
                        end: date[1],
                        free: $statusFree.mobiscroll('getInst').checked,
                        color: ev.color,
                    });

                    // navigate the calendar to the correct view
                    calendar.navigate(date[0]);;

                    restoreEvent = false;
                    popup.close();
                },
                cssClass: 'mbsc-popup-button-primary'
            }]
        });

        // fill popup with the selected event data
        $title.mobiscroll('getInst').value = ev.title || '';
        $description.mobiscroll('getInst').value = ev.description || '';
        $allDay.mobiscroll('getInst').checked = ev.allDay || false;
        range.setVal([ev.start, ev.end]);
        selectColor(ev.color, true);

        if (ev.free) {
            $statusFree.mobiscroll('getInst').checked = true;
        } else {
            $statusBusy.mobiscroll('getInst').checked = true;
        }

        // change range settings based on the allDay
        range.setOptions({
            controls: ev.allDay ? ['date'] : ['datetime'],
            responsive: ev.allDay ? datePickerResponsive : datetimePickerResponsive
        });

        // set anchor for the popup
        popup.setOptions({ anchor: args.domEvent.currentTarget });
        popup.open();
    }

    var calendar = $('#demo-add-delete-event').mobiscroll().eventcalendar({
        clickToCreate: 'double',
        dragToCreate: true,
        dragToMove: true,
        dragToResize: true,
        view: {
            calendar: { labels: true }
        },
        data: myData,
        onEventClick: function (args) {
            oldEvent = $.extend({}, args.event);
            tempEvent = args.event;

            if (!popup.isVisible()) {
                createEditPopup(args);
            }
        },
        onEventCreated: function (args) {
            popup.close();

            // store temporary event
            tempEvent = args.event;
            createAddPopup(args.target);
        },
        onEventDeleted: function () {
            mobiscroll.snackbar({
                button: {
                    action: function () {
                        calendar.addEvent(args.event);
                    },
                    text: 'Undo'
                },
                message: 'Event deleted'
            });
        }
    }).mobiscroll('getInst');

    var popup = $('#demo-add-popup').mobiscroll().popup({
        display: 'bottom',
        contentPadding: false,
        fullScreen: true,
        onClose: function () {
            if (deleteEvent) {
                calendar.removeEvent(tempEvent);
            } else if (restoreEvent) {
                calendar.updateEvent(oldEvent);
            }
        },
        responsive: {
            medium: {
                display: 'anchored',
                width: 400,
                fullScreen: false,
                touchUi: false
            }
        }
    }).mobiscroll('getInst');

    $title.on('input', function (ev) {
        // update current event's title
        tempEvent.title = ev.target.value;
    });

    $description.on('change', function (ev) {
        // update current event's title
        tempEvent.description = ev.target.value;
    });

    $allDay.on('change', function () {
        var checked = this.checked

        // change range settings based on the allDay
        range.setOptions({
            controls: checked ? ['date'] : ['datetime'],
            responsive: checked ? datePickerResponsive : datetimePickerResponsive
        });

        // update current event's allDay property
        tempEvent.allDay = checked;
    });

    var range = $('#event-date').mobiscroll().datepicker({
        controls: ['date'],
        select: 'range',
        startInput: '#start-input',
        endInput: '#end-input',
        showRangeLabels: false,
        touchUi: true,
        responsive: datePickerResponsive,
        onChange: function (args) {
            var date = args.value;

            // update event's start date
            tempEvent.start = date[0];
            tempEvent.end = date[1];
        }
    }).mobiscroll('getInst');

    $('input[name=event-status]').on('change', function () {
        // update current event's free property
        tempEvent.free = $statusFree.mobiscroll('getInst').checked;
    });

    $deleteButton.on('click', function () {
        // delete current event on button click
        calendar.removeEvent(oldEvent);

        popup.close();

        mobiscroll.snackbar({
            button: {
                action: function () {
                    calendar.addEvent(tempEvent);
                },
                text: 'Undo'
            },
            message: 'Event deleted'
        });
    });

    colorPicker = $('#demo-event-color').mobiscroll().popup({
        display: 'bottom',
        contentPadding: false,
        showArrow: false,
        showOverlay: false,
        buttons: [
            'cancel',
            {
                text: 'Set',
                keyCode: 'enter',
                handler: function (ev) {
                    setSelectedColor();
                },
                cssClass: 'mbsc-popup-button-primary'
            }
        ],
        responsive: {
            medium: {
                display: 'anchored',
                anchor: $('#event-color-cont')[0],
                buttons: {},
            }
        }
    }).mobiscroll('getInst');

    function selectColor(color, setColor) {
        $('.crud-color-c').removeClass('selected');
        $('.crud-color-c[data-value="' + color + '"]').addClass('selected');
        if (setColor) {
            $color.css('background', color || '');
        }
    }

    function setSelectedColor() {
        tempEvent.color = tempColor;
        $color.css('background', tempColor);
        colorPicker.close();
    }

    $('#event-color-picker').on('click', function () {
        selectColor(tempEvent.color || '');
        colorPicker.open();
    });


    $('.crud-color-c').on('click', function (ev) {
        var $elm = $(ev.currentTarget);

        tempColor = $elm.data('value');
        selectColor(tempColor);

        if (!colorPicker.s.buttons.length) {
            setSelectedColor();
        }
    });
});
