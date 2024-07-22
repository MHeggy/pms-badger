<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $pageTitle = 'Payroll [Accountant]' ?></title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
    <style>
        .employee-box {
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
    <input type="text" name="search" class="form-control search-input" placeholder="Search by username...">
    <button type="submit" class="btn btn-primary search-btn">Search</button>
</form>

<!-- Accountant payroll page content starts here -->
<?php if (!empty($userData)): ?>
    <?php foreach ($userData as $user): ?>
        <div class="employee-box">
            <h4><?= $user->username ?></h4>
            <p>Email: <?= $user->email ?></p>
            <p>Name: <?= $user->first_name . ' ' . $user->last_name ?></p>
            <!-- Add a link to view timesheets for this employee -->
            <a href="/timesheets/user/<?= $user->id ?>">View Timesheets</a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No users found.</p>
<?php endif; ?>

<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>