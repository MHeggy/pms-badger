<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $pageTitle = "Calendar" ?></title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Script for FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <!-- Script for jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/calendar.css')?>">
</head>
<body>
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
                    <div class="form-check">
                        <input type="checkbox" id="allDay" name="all_day" class="form-check-input">
                        <label for="allDay" class="form-check-label">All Day Event</label>
                    </div><br>
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
                    <div class="form-check">
                        <input type="checkbox" id="editAllDay" name="all_day" class="form-check-input">
                        <label for="editAllDay" class="form-check-label">All Day Event</label>
                    </div><br>
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
            selectable: true,
            events: <?= $events ?>,
            select: function(info) {
                // Open the modal when a date is selected
                $('#addEventModal').modal('show');
                // Automatically fill the start and end date fields with the selected date
                $('#start').val(new Date(info.start).toISOString().slice(0, 16));
                $('#end').val(info.end ? new Date(info.end).toISOString().slice(0, 16) : new Date(info.start).toISOString().slice(0, 16));
                $('#allDay').prop('checked', false); // Reset checkbox
            },
            eventClick: function(info) {
                // Show the modal for editing
                $('#editEventModal').modal('show');
                // Fill the form with event details
                $('#editEventForm input[name="eventId"]').val(info.event.id);
                $('#editTitle').val(info.event.title);
                $('#editStart').val(new Date(info.event.start).toISOString().slice(0, 16));
                $('#editEnd').val(info.event.end ? new Date(info.event.end).toISOString().slice(0, 16) : '');
                $('#editAllDay').prop('checked', info.event.extendedProps.all_day); // Set checkbox based on event data
            }
        });

        calendar.render();

        // Handle event form submission
        $('#eventForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            // Get form data
            var title = $('#title').val();
            var start = $('#start').val();
            var end = $('#end').val();
            var allDay = $('#allDay').is(':checked') ? 1 : 0; // Use 1 for true, 0 for false

            // Submit form data via AJAX
            $.ajax({
                type: 'POST',
                url: '/calendar/create',
                data: {
                    title: title,
                    start_date: start,
                    end_date: end,
                    all_day: all_day
                },
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    // Reload the page or update the calendar view
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(error);
                }
            });
        });

        // Handle event form submission for updates
        $('#editEventForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            // Get form data
            var eventId = $('#editEventForm input[name="eventId"]').val();
            var title = $('#editTitle').val();
            var start = $('#editStart').val();
            var end = $('#editEnd').val();
            var allDay = $('#editAllDay').is(':checked') ? 1 : 0; // Use 1 for true, 0 for false

            // Submit form data via AJAX
            $.ajax({
                type: 'POST',
                url: '/calendar/updateEvent',
                data: {
                    eventId: eventId,
                    title: title,
                    start_date: start,
                    end_date: end,
                    all_day: allDay
                },
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    // Reload the page or update the calendar view
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(error);
                }
            });
        });

        // Delete Event button click handler
        $('#deleteEventBtn').click(function() {
            // Capture the event ID
            var eventId = $('#editEventForm input[name="eventId"]').val();

            // Make an AJAX request to delete the event
            $.ajax({
                type: 'POST',
                url: '/calendar/deleteEvent',
                data: { eventId: eventId },
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    // Reload the page or update the calendar view
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(error);
                }
            });
        });
    });
</script>
</body>
</html>
