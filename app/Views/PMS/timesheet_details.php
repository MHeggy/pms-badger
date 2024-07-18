<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php $pageTitle = 'Timesheet Details' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
    <?php include 'header.php'; ?>
</header>

<div class="container mt-5">
    <button onclick="goBack()" class="btn btn-primary mb-3">Back to Timesheets</button>
    <h2>Timesheet for the week of <?= esc($timesheet['week_of']) ?></h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Day</th>
            <th>Hours Worked</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Monday</td>
            <td><?= esc($dailyHours['monday']) ?></td>
        </tr>
        <tr>
            <td>Tuesday</td>
            <td><?= esc($dailyHours['tuesday']) ?></td>
        </tr>
        <tr>
            <td>Wednesday</td>
            <td><?= esc($dailyHours['wednesday']) ?></td>
        </tr>
        <tr>
            <td>Thursday</td>
            <td><?= esc($dailyHours['thursday']) ?></td>
        </tr>
        <tr>
            <td>Friday</td>
            <td><?= esc($dailyHours['friday']) ?></td>
        </tr>
        <tr>
            <td>Saturday</td>
            <td><?= esc($dailyHours['saturday']) ?></td>
        </tr>
        <tr>
            <td>Sunday</td>
            <td><?= esc($dailyHours['sunday']) ?></td>
        </tr>
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