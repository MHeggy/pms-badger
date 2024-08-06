<?php $pageTitle = 'Payroll [Accountant]' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .week-box {
            margin: 120px; /* Adjusted margin */
            padding: 20px;
            border: 1px solid #ccc;
        }
        .search-form {
            margin-top: 80px; /* Adjusted margin-top */
            margin-left: auto;
            margin-right: auto;
            width: 50%; /* Adjusted width */
            max-width: 400px; /* Adjusted max-width */
            display: flex;
        }
        .search-input {
            flex: 1;
            margin-right: 10px;
        }
        .search-btn {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
<header>
    <?php include 'header.php' ?>
</header>

<!-- Search form -->
<form action="<?= base_url('/search_payroll') ?>" method="get" class="search-form">
    <input type="text" name="search" class="form-control search-input" placeholder="Search by week...">
    <button type="submit" class="btn btn-primary search-btn">Search</button>
</form>

<!-- Accountant payroll page content starts here -->
<?php if (!empty($timesheetData)): ?>
    <?php foreach ($timesheetData as $weekOf => $timesheets): ?>
        <div class="week-box">
            <h4>Week of: <?= date('F j, Y', strtotime($weekOf)) ?></h4>
            <ul>
                <?php foreach ($timesheets as $timesheet): ?>
                    <li>
                        <?= $timesheet->username ?> (<?= $timesheet->email ?>) - 
                        <a href="/timesheets/view/<?= $timesheet->timesheetID ?>">View Timesheet</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No timesheets found.</p>
<?php endif; ?>

<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
