<?= $pageTitle = "Calendar" ?>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Script for FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <!-- Script for jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/calendar.css')?>">
<header>
    <?php include 'header.php' ?>
</header>

<!-- Calendar Section -->
<div id="calendar"></div>

<!-- Modal for Adding Event -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="eventForm" method="post" action="/calendar/create">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required class="form-control"><br><br>
                    <label for="start">Start Date:</label>
                    <input type="datetime-local" id="start" name="start" required class="form-control"><br><br>
                    <label for="end">End Date:</label>
                    <input type="datetime-local" id="end" name="end" class="form-control"><br><br>
                    <br>
                    <button type="submit" class="btn btn-primary" id="addEventBtn">Add Event</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Editing Event -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEventForm" method="post" action="/calendar/update">
                    <input type="hidden" name="eventId" id="eventId">
                    <label for="editTitle">Title:</label>
                    <input type="text" id="editTitle" name="title" required class="form-control"><br><br>
                    <label for="editStart">Start Date:</label>
                    <input type="datetime-local" id="editStart" name="start" required class="form-control"><br><br>
                    <label for="editEnd">End Date:</label>
                    <input type="datetime-local" id="editEnd" name="end" class="form-control"><br><br>
                    <br>
                    <button type="submit" class="btn btn-primary" id="updateEventBtn">Update Event</button>
                    <button type="button" class="btn btn-danger" id="deleteEventBtn">Delete Event</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to Initialize FullCalendar and Handle Events -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        displayEventTime: true,
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        timezone: 'utc',
        selectable: true,
        events: <?= $events ?>,
        select: function(info) {
            $('#addEventModal').modal('show');
            $('#start').val(new Date(info.start).toISOString().slice(0, 16));
            $('#end').val(info.end ? new Date(info.end).toISOString().slice(0, 16) : new Date(info.start).toISOString().slice(0, 16));
            $('#allDay').prop('checked', false);
        },
        eventClick: function(info) {
            $('#editEventModal').modal('show');
            $('#editEventForm input[name="eventId"]').val(info.event.id);
            $('#editTitle').val(info.event.title);
            // Convert the start and end times to local time strings
            var startLocal = new Date(info.event.start).toLocaleString('sv-SE', { timeZoneName: 'short' }).slice(0, 16);
            var endLocal = info.event.end ? new Date(info.event.end).toLocaleString('sv-SE', { timeZoneName: 'short' }).slice(0, 16) : '';

            $('#editStart').val(startLocal);
            $('#editEnd').val(endLocal);
        },
        eventContent: function(arg) {
            let eventTime = arg.event.end ? arg.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
            return {
                html: '<div class="fc-event-title fc-sticky">' + arg.event.title + '</div>' +
                      '<div class="fc-event-time">' + eventTime + '</div>'
            };
        }
    });

    calendar.render();

    $('#eventForm').submit(function(e) {
    e.preventDefault();
    var title = $('#title').val();
    var start = $('#start').val();
    var end = $('#end').val();

    $.ajax({
        type: 'POST',
        url: '/calendar/create',
        data: {
            title: title,
            start_date: start,
            end_date: end
        },
        success: function(response) {
            window.location.reload();
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

$('#editEventForm').submit(function(e) {
    e.preventDefault();
    var eventId = $('#editEventForm input[name="eventId"]').val();
    var title = $('#editTitle').val();
    var start = $('#editStart').val();
    var end = $('#editEnd').val();

    $.ajax({
        type: 'POST',
        url: '/calendar/updateEvent',
        data: {
            eventId: eventId,
            title: title,
            start_date: start,
            end_date: end
        },
        success: function(response) {
            window.location.reload();
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});


    $('#deleteEventBtn').click(function() {
        var eventId = $('#editEventForm input[name="eventId"]').val();

        $.ajax({
            type: 'POST',
            url: '/calendar/deleteEvent',
            data: { eventId: eventId },
            success: function(response) {
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
</script>
</body>
</html>