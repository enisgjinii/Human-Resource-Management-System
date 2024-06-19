<script>
document.addEventListener('DOMContentLoaded', () => {
    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        themeSystem: 'bootstrap',
        events: <?php echo json_encode($events); ?>,
        editable: true,
        eventClick: ({
            event
        }) => {
        Swal.fire({
            title: 'Event',
            text: 'Event: ' + event.title,
            icon: 'info',
            confirmButtonText: 'OK'
        });
    },
        dateClick: ({
            dateStr
        }) => {
        Swal.fire({
            title: 'Enter Event Title',
            input: 'text',
            showCancelButton: true,
            confirmButtonText: 'Add Event',
            cancelButtonText: 'Cancel',
            preConfirm: (title) => {
                if (title) {
                    calendar.addEvent({
                        title: title,
                        start: dateStr,
                        allDay: true
                    });
                }
            }
        });
    }
    });
calendar.render();
const checkboxes = document.querySelectorAll('.calendar-checkbox');
checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', () => {
        const selectedCalendars = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        const filteredEvents = <?php echo json_encode($events); ?>.filter(event => selectedCalendars.includes(event.calendar));
        calendar.removeAllEvents();
        calendar.addEventSource(filteredEvents);
    });
});
});
</script>