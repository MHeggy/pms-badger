<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle = 'Edit Timesheet' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 80px;
        }

        .container {
            max-width: 600px;
        }
    </style>
</head>
<body>
<!-- Header content here -->
<header>
    <?php include 'header.php' ?>
</header>
<br><br>
<div class="container mt-5">
    <form action="/timesheets/update" method="post">
        <input type="hidden" name="id" value="<?= $timesheet['id'] ?>">
        <div class="mb-3">
            <label for="week" class="form-label">Week</label>
            <input type="date" class="form-control" id="week" name="week" value="<?= $timesheet['week_of'] ?>" required>
        </div>
        <!-- Input fields for each day of the week -->
        <!-- Inside the form -->
        <?php foreach ($daysOfWeek as $day): ?>
            <div class="mb-3">
                <label for="<?= strtolower($day) ?>" class="form-label"><?= $day ?></label>
                <!-- Check if daily hours exist for the current day -->
                <?php $dayValue = isset($dailyHours[$day]) ? $dailyHours[$day] : ''; ?>
                <input type="text" step="0.01" class="form-control" id="<?= strtolower($day) ?>" name="<?= strtolower($day) ?>" onchange="calculateTotalHours()" value="<?= $dayValue ?>">
            </div>
        <?php endforeach; ?>


        <!-- Total hours field (read-only) -->
        <div class="mb-3">
            <label for="hours_worked" class="form-label">Total Hours Worked</label>
            <input type="text" class="form-control" id="hours_worked" name="hours_worked" readonly>
        </div>
        <button type="submit" class="btn btn-primary">Update Timesheet</button>
    </form>
</div>

<script>
    // Function to calculate total hours
    function calculateTotalHours() {
        let totalHours = 0;

        // Get values entered by the user for each day of the week
        const daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        daysOfWeek.forEach(day => {
            const input = document.getElementById(day);
            if (input.value !== '') {
                totalHours += parseFloat(input.value);
            }
        });

        // Display the total hours on the page
        const totalHoursElement = document.getElementById('hours_worked');
        totalHoursElement.value = totalHours;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
