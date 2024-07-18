<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle = "People"; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/people.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Header content -->
<header>
    <?php include 'header.php' ?>
</header>

<!-- People table -->
<div class="container mt-5">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><a href="<?= base_url('/peopleDetails') ?>"><?= $user->username ?></a></td>
                <td><?= $user->first_name ?></td>
                <td><?= $user->last_name ?></td>
                <td><?= $user->email ?></td>
                <td>
                    <!-- Add buttons for actions here -->
                    <button class="btn btn-primary">View Details</button>
                    <button class="btn btn-secondary">Send Message</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>