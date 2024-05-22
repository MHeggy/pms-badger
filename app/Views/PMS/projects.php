<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle = "Projects"; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/projects.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Header content -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

<div class="container" id="filter-container" style="margin-top: 100px;">
    <div class="row justify-content-center"> <!-- Centering the row -->
        <div class="col-md-6">
            <div class="search-filter-container">
                <div class="card search-filter-card">
                    <div class="card-body">
                        <h5 class="card-title">Search Projects</h5>
                        <form action="<?= base_url('projects/search') ?>" method="get">
                            <!-- Search input -->
                            <input type="text" id="search" name="search" class="form-control" placeholder="Search Projects by name">
                            <!-- Apply button -->
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="search-filter-container">
                <div class="card search-filter-card">
                    <div class="card-body">
                        <h5 class="card-title">Filter Projects</h5>
                        <form action="<?= base_url('projects/filter') ?>" method="get">
                            <!-- Status select -->
                            <select name="status" id="status" class="form-select">
                                <option value="">All Projects</option>
                                <option value="1">In Progress</option>
                                <option value="2">Completed</option>
                                <option value="3">Cancelled</option>
                                <option value="4">Postponed</option>
                            </select>
                            <!-- Apply button -->
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container" id="project_table" style="margin-top: 20px;">
    <!-- Project table goes here -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Project Number</th>
            <th>Project Name</th>
            <th>Project Status</th>
            <th>Assigned Users</th>
        </tr>
        </thead>
        <tbody id="project_list">
        <?php foreach ($projects as $project): ?>
            <tr data-project-id="<?= $project['projectID'] ?>">
                <td><?= $project['projectNumber'] ?></td>
                <td><?= $project['projectName'] ?></td>
                <td><?= $project['statusName'] ?></td>
                <td>
                    <?php if (!empty($project['assignedUsers'])): ?>
                        <ul>
                            <?php foreach ($project['assignedUsers'] as $user): ?>
                                <li><?= esc($user['username']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        No users assigned.
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Initialize modal -->
<div id="projectDetailsModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Project Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="projectDetails"></div>
        </div>
    </div>

</div>
<script src="<?php echo base_url('/assets/js/projects.js')?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
