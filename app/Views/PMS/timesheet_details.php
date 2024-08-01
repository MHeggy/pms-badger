<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= esc($pageTitle = 'Timesheet Details') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        th, td {
            text-align: center;
        }
        .container {
            margin-top: 85px;
        }
        .btn-back {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<header>
    <?php include 'header.php'; ?>
</header>

<div class="container">
    <button onclick="goBack()" class="btn btn-primary btn-back">Back to Timesheets</button>
    <h2>Timesheet for the week of <?= esc($timesheet['weekOf']) ?></h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Project Number</th>
            <th>Project Name</th>
            <th>Activity Description</th>
            <th>Monday</th>
            <th>Tuesday</th>
            <th>Wednesday</th>
            <th>Thursday</th>
            <th>Friday</th>
            <th>Saturday</th>
            <th>Sunday</th>
            <th>Total Hours</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($timesheetEntries as $entry): ?>
            <tr>
                <td><?= esc($entry['projectNumber']) ?></td>
                <td><?= esc($entry['projectName']) ?></td>
                <td><?= esc($entry['activityDescription']) ?></td>
                <td><?= esc($entry['mondayHours']) ?></td>
                <td><?= esc($entry['tuesdayHours']) ?></td>
                <td><?= esc($entry['wednesdayHours']) ?></td>
                <td><?= esc($entry['thursdayHours']) ?></td>
                <td><?= esc($entry['fridayHours']) ?></td>
                <td><?= esc($entry['saturdayHours']) ?></td>
                <td><?= esc($entry['sundayHours']) ?></td>
                <td><?= esc($entry['totalHours']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>

<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
