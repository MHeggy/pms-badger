<?php esc($pageTitle = 'Timesheet Details') ?>

<!-- header content -->
<header>
    <?php include 'header.php'; ?>
</header>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<style>
    body {
        background-color: #f8f9fa;
    }
    .container {
        margin-top: 85px;
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    h2 {
        color: #0056b3; /* Blue color for the title */
        margin-bottom: 20px;
    }
    table th {
        background-color: #0056b3; /* Blue for table header */
        color: black;
    }
    th, td {
        text-align: center;
        vertical-align: middle;
    }
    .btn-back {
        background-color: #0056b3; /* Same blue for the back button */
        color: white;
        margin-bottom: 20px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        padding: 10px 15px;
        text-transform: uppercase;
    }
    .btn-back i {
        margin-right: 10px;
    }
    .total-hours {
        font-weight: bold;
        color: #0056b3;
    }
    tfoot td {
        font-size: 1.1em;
    }
</style>

<div class="container">
    <button onclick="goBack()" class="btn btn-back">
        <i class="fas fa-arrow-left"></i> Go Back
    </button>
    <h2>Timesheet for the week of <?= esc($timesheet['weekOf']) ?></h2>
    <table class="table table-bordered table-striped">
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
        <tfoot>
        <tr>
            <td colspan="10" class="text-end">Total Hours for this week:</td>
            <td class="total-hours"><?= esc($totalHours) ?></td>
        </tr>
        </tfoot>
    </table>
</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>

<script src="<?php echo base_url('/assets/js/main.js')?>"></script>