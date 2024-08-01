<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle = 'Edit Timesheet') ?></title>
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
<header>
    <?php include 'header.php'; ?>
</header>

<div class="container mt-5">
    <form action="/timesheets/update" method="post">
        <input type="hidden" name="id" value="<?= esc($timesheet['timesheetID']) ?>">
        
        <div class="mb-3">
            <label for="week" class="form-label">Week</label>
            <input type="date" class="form-control" id="week" name="week" value="<?= esc($timesheet['weekOf']) ?>" required>
        </div>

        <!-- Input fields for each day of the week -->
        <?php foreach ($daysOfWeek as $day): ?>
            <div class="mb-3">
                <label for="<?= strtolower($day) ?>" class="form-label"><?= $day ?></label>
                <?php
                // Extract the day value from the entries array
                $dayValue = 0;
                foreach ($entries as $entry) {
                    $dayValue += isset($entry[strtolower($day).'Hours']) ? floatval($entry[strtolower($day).'Hours']) : 0;
                }
                ?>
                <input type="number" step="0.01" class="form-control" id="<?= strtolower($day) ?>" name="<?= strtolower($day) ?>" onchange="calculateTotalHours()" value="<?= esc($dayValue) ?>">
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
            if (input && input.value !== '') {
                totalHours += parseFloat(input.value);
            }
        });

        // Display the total hours on the page
        const totalHoursElement = document.getElementById('hours_worked');
        totalHoursElement.value = totalHours.toFixed(2); // Format to 2 decimal places
    }

    // Initialize total hours when the page loads
    window.onload = function() {
        calculateTotalHours();
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
