<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle = "Timesheets"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #timesheet-container {
            margin-bottom: 50px;
        }

        .view-timesheets-card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<!-- Header content -->
<header>
    <?php include 'header.php' ?>
</header>
<br><br>
<!-- Section to allow user to view their own timesheets -->
<div class="container mt-3">
    <div class="card view-timesheets-card">
        <div class="card-body">
            <h5 class="card-title">View Timesheets</h5>
            <p class="card-text">Click below to view your timesheets.</p>
            <a href="/timesheets/user/<?= $user->id ?>" class="btn btn-primary">My Timesheets</a>
        </div>
    </div>
</div>

<!-- Timesheet form -->
<div class="container mt-5" id="timesheet-container">
    <form id="timesheet-form" action="/submit-timesheets" method="post">
        <input type="hidden" id="user-id" name="user-id" value="<?= $userId; ?>">
        <div class="row mb-3">
            <label for="week" class="col-sm-2 col-form-label">Week</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" id="week" name="week" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="project" class="col-sm-2 col-form-label">Project</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="project" name="project">
            </div>
        </div>
        <div class="row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="description" name="description">
            </div>
        </div>
        <!-- Add input fields for each day of the week -->
        <div class="row mb-3">
            <label for="monday" class="col-sm-2 col-form-label">Monday</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="monday" name="monday">
            </div>
        </div>
        <div class="row mb-3">
            <label for="tuesday" class="col-sm-2 col-form-label">Tuesday</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="tuesday" name="tuesday">
            </div>
        </div>
        <div class="row mb-3">
            <label for="wednesday" class="col-sm-2 col-form-label">Wednesday</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="wednesday" name="wednesday">
            </div>
        </div>
        <div class="row mb-3">
            <label for="thursday" class="col-sm-2 col-form-label">Thursday</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="thursday" name="thursday">
            </div>
        </div>
        <div class="row mb-3">
            <label for="friday" class="col-sm-2 col-form-label">Friday</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="friday" name="friday">
            </div>
        </div>
        <div class="row mb-3">
            <label for="saturday" class="col-sm-2 col-form-label">Saturday</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="saturday" name="saturday">
            </div>
        </div>
        <div class="row mb-3">
            <label for="sunday" class="col-sm-2 col-form-label">Sunday</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="sunday" name="sunday">
            </div>
        </div>

        <!-- Total hours field -->
        <div class="row mb-3">
            <label for="total-hours" class="col-sm-2 col-form-label">Total Hours</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="total-hours" name="total-hours" readonly>
            </div>
        </div>

        <!-- Submit button -->
        <div class="row mt-3">
            <div class="col-sm-10 offset-sm-2">
                <button id="submit-timesheet" type="submit" class="btn btn-primary">Submit Timesheet</button>
            </div>
        </div>
    </form>
</div>

<!-- Simple little script to calculate the total hours a user is submitting -->
<script>
    function calculateTotalHours() {
        let totalHours = 0;

        // get values entered by the user.
        const daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        daysOfWeek.forEach(day => {
            const input = document.getElementById(day);
            if (input.value !== '') {
                totalHours += parseFloat(input.value);
            }
        });

        // display the total hours on the page.
        const totalHoursElement = document.getElementById('total-hours');
        totalHoursElement.value = totalHours;
    }

    // Event listener to calculate total hours whenever any input field changes
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', calculateTotalHours);
    });
</script>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
