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

    <script src="<?php echo base_url('/assets/js/projects.js')?>"></script>
    <div class="container" id="filter-container" style="margin-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="search-filter-container">
                    <div class="card search-filter-card">
                        <div class="card-body">
                            <h5 class="card-title">Search Projects</h5>
                            <form id="searchForm">
                                <input type="text" id="search" name="search" class="form-control" placeholder="Search Projects by name">
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
                            <form id="filterForm" action="<?= base_url('projects/filter') ?>" method="get">
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Projects</option>
                                    <option value="1">In Progress</option>
                                    <option value="2">Completed</option>
                                    <option value="3">Cancelled</option>
                                    <option value="4">Postponed</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Apply</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="project_table" style="margin-top: 20px;">
        <table class="table table-striped">
            <!-- Table headers -->
            <thead>
            <tr>
                <th>
                    Project Number
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-primary" onclick="sortProjects('asc')">
                            <i class="bi bi-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" onclick="sortProjects('desc')">
                            <i class="bi bi-arrow-down"></i>
                        </button>
                    </div>
                </th>
                <th>Project Name</th>
                <th>Project Status</th>
                <th>Category</th> <!-- New Column -->
                <th>Date Accepted</th>
                <th>Assigned Users</th>
            </tr>
            </thead>
            <!-- Table body -->
            <tbody id="project_list">
            <?php foreach ($projects as $project): ?>
                <!-- Table rows -->
                <tr data-project-id="<?= $project['projectID'] ?>">
                    <td>
                        <a href="<?= base_url('projects/details/' . $project['projectID']) ?>">
                            <?= $project['projectID'] ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?= base_url('projects/details/' . $project['projectID']) ?>">
                            <?= esc($project['projectName']) ?>
                        </a>
                    </td>
                    <td><?= esc($project['statusName']) ?></td>
                    <td><?= esc($project['categoryNames']) ?></td> <!-- New Data -->
                    <td><?= esc($project['dateAccepted']) ?></td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
