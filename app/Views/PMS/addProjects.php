<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $pageTitle = '[Admin] Add Projects' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
</head>
<body>
<!-- Header content -->
<header>
    <?php include 'header.php' ?>
</header>
<!-- Body content starts here -->
<div class="container mt-5"><br><br><br>
    <form action="<?php echo base_url('/projects/add') ?>" name="addProject" class="row g-3" method="post">
        <div class="col-md-6">
            <label for="project_name" class="form-label">Project Name</label>
            <input type="text" class="form-control" id="project_name" name="project_name" placeholder="Project Name" required>
        </div>
        <div class="col-md-6">
            <label for="project_name" class="form-label">Project Number</label>
            <input type="text" class="form-control" id="project_number" name="project_number" placeholder="Project Number" required>
        </div>
        <div class="col-md-6">
            <label for="date_accepted" class="form-label">Date Accepted</label>
            <input type="date" class="form-control" id="date_accepted" name="date_accepted" required>
        </div>
        <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status">
                <option value="1">In Progress</option>
                <option value="2">Completed</option>
                <option value="3">Canceled</option>
                <option value="4">Postponed</option>
            </select>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>