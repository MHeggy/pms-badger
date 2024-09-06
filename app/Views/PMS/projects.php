<?= $pageTitle = "Projects"; ?>
<link rel="stylesheet" href="<?php echo base_url('/assets/css/projects.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
<!-- Header content -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

<!-- Success Message Section -->
<?php if (session()->getFlashdata('success')): ?>
    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<script src="<?= base_url('/assets/js/projects.js') ?>"></script>

<div class="container" id="filter-container" style="margin-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="search-filter-container">
                <div class="card search-filter-card">
                    <div class="card-body">
                        <h5 class="card-title">Search Projects</h5>
                        <form id="searchForm" action="<?= base_url('projects/search') ?>" method="get">
                            <input type="text" id="search" name="search" class="form-control" 
                                   placeholder="Search Projects by name" value="<?= esc($searchTerm ?? '') ?>">
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
            <th>Project Number</th>
            <th>Project Name</th>
            <th>Project Status</th>
            <th>Category</th>
            <th>Date Accepted</th>
            <th>Assigned Users</th>
            <?php if ($user1->inGroup('superadmin')): // Check if the user is in the 'admin' group ?>
                <th>Edit</th>
            <?php endif; ?>
        </tr>
        </thead>
        <!-- Table body -->
        <tbody id="project_list">
        <?php if (!empty($projects)): ?>
            <?php foreach ($projects as $project): ?>
                <!-- Table rows -->
                <tr data-project-id="<?= esc($project['projectID']) ?>">
                    <td>
                        <a href="<?= base_url('projects/details/' . $project['projectID']) ?>">
                            <?= esc($project['projectNumber']) ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?= base_url('projects/details/' . $project['projectID']) ?>">
                            <?= esc($project['projectName']) ?>
                        </a>
                    </td>
                    <td><?= esc($project['statusName']) ?></td>
                    <td><?= esc(str_replace(',', ', ', $project['categoryNames'])) ?></td>
                    <td><?= esc($project['dateAccepted']) ?></td>
                    <td>
                        <?php if (!empty($project['assignedUsers'])): ?>
                            <?php
                                // Check if 'assignedUsers' is a string before exploding it
                                $users = is_string($project['assignedUsers']) ? explode(',', $project['assignedUsers']) : [];
                            ?>
                        <ul>
                            <?php foreach ($users as $user): ?>
                                <li><?= esc(trim($user)) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        No users assigned.
                    <?php endif; ?>
                    </td>

                    <?php if ($user1->inGroup('superadmin')): ?>
                        <td>
                            <a href="<?= base_url('projects/edit/' . $project['projectID']) ?>" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No projects found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
<script>
    // JavaScript for fading out the success message
    document.addEventListener('DOMContentLoaded', function () {
        var successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(function () {
                successMessage.classList.add('fade');
                successMessage.addEventListener('transitionend', function () {
                    successMessage.remove();
                });
            }, 3000); // Adjust time as needed
        }
    });
</script>
</body>
</html>