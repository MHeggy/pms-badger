<?php $pageTitle = "Projects"; ?>

<!-- Header content -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

<link rel="stylesheet" href="<?php echo base_url('/assets/css/projects.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }
    .badge-success {
        background-color: #28a745; /* Green for 'In Progress' */
        color: #fff; /* White text */
    }
    .badge-primary {
        background-color: #007bff; /* Blue for 'Completed' */
        color: #fff; /* White text */
    }
    .badge-warning {
        background-color: #ffc107; /* Yellow for 'Cancelled' or 'Postponed' */
        color: #212529; /* Dark text */
    }
    .btn-clear {
        display: none; /* Initially hidden */
    }
</style>

<!-- Success Message Section -->
<?php if (session()->getFlashdata('success')): ?>
    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<br><br><br>

<div class="container" id="project_table" style="margin-top: 20px;">
    <!-- Title -->
    <h1 class="text-center mb-4">All Projects</h1>

    <!-- Search and Filter Container Above the Table -->
    <div class="row mb-4 search-filter-container">
        <form id="searchForm" action="<?= base_url('projects/search') ?>" method="get" class="position-relative">
            <input type="text" id="search" name="search" class="form-control" placeholder="Search Projects" value="<?= esc($searchTerm ?? '') ?>">
            <button type="submit" id="searchButton" class="btn">
                <i class="bi bi-search"></i>
            </button>
            <button type="button" id="clearSearchButton" class="btn btn-clear">
                <i class="bi bi-x-circle"></i>
            </button>
        </form>

        <div class="dropdown">
            <button id="filterToggle" class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-filter"></i> Filter
            </button>
            <ul id="filterOptions" class="dropdown-menu p-3 collapse">
                <form id="filterForm" action="<?= base_url('projects/filter') ?>" method="get">
                    <div class="mb-3">
                        <label for="status" class="form-label">Project Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Projects</option>
                            <option value="1" <?= (isset($selectedFilters['status']) && $selectedFilters['status'] == '1') ? 'selected' : '' ?>>In Progress</option>
                            <option value="2" <?= (isset($selectedFilters['status']) && $selectedFilters['status'] == '2') ? 'selected' : '' ?>>Completed</option>
                            <option value="3" <?= (isset($selectedFilters['status']) && $selectedFilters['status'] == '3') ? 'selected' : '' ?>>Cancelled</option>
                            <option value="4" <?= (isset($selectedFilters['status']) && $selectedFilters['status'] == '4') ? 'selected' : '' ?>>Postponed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['categoryID'] ?>" <?= (isset($selectedFilters['category']) && $selectedFilters['category'] == $cat['categoryID']) ? 'selected' : '' ?>>
                                    <?= $cat['categoryName'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </form>
            </ul>
        </div>

        <div id="activeFilters" class="d-none mt-4">
            <span id="statusFilter" class="badge bg-secondary me-2 d-none">
                Status: <span id="statusName"></span> <i class="bi bi-x" id="clearStatus"></i>
            </span>
            <span id="categoryFilter" class="badge bg-secondary me-2 d-none">
                Category: <span id="categoryName"></span> <i class="bi bi-x" id="clearCategory"></i>
            </span>
        </div>
    </div>

    <!-- Table for Projects -->
    <div class="table-responsive">
        <table class="table align-middle mb-0 bg-white">
            <thead class="bg-light">
                <tr>
                    <th>Project Number
                        <span class="sort-arrow">
                            <i class="bi bi-arrow-up" id="sortAsc"></i>
                            <i class="bi bi-arrow-down" id="sortDesc"></i>
                        </span>
                    </th>
                    <th>Project Name</th>
                    <th>Project Status</th>
                    <th>Category</th>
                    <th>Date Accepted</th>
                    <th>Assigned Users</th>
                    <?php if ($user1->inGroup('superadmin')): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="project_list">
                <?php if (!empty($projects)): ?>
                    <?php foreach ($projects as $project): ?>
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
                            <td>
                                <span class="badge <?= $project['statusName'] == 'In Progress' ? 'badge-success' : ($project['statusName'] == 'Completed' ? 'badge-primary' : 'badge-warning'); ?> rounded-pill">
                                    <?= esc($project['statusName']) ?>
                                </span>
                            </td>
                            <td><?= esc(str_replace(',', ', ', $project['categoryNames'])) ?></td>
                            <td><?= esc($project['dateAccepted']) ?></td>
                            <td>
                                <?php if (!empty($project['assignedUsers']) && is_array($project['assignedUsers'])): ?>
                                    <ul class="list-unstyled">
                                        <?php foreach ($project['assignedUsers'] as $user): ?>
                                            <li><?= esc($user['username']) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    No users assigned.
                                <?php endif; ?>
                            </td>
                            <?php if ($user1->inGroup('superadmin')): ?>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton-<?= $project['projectID'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-<?= $project['projectID'] ?>">
                                            <li><a class="dropdown-item" href="<?= base_url('projects/edit/' . $project['projectID']) ?>">
                                                <i class="bi bi-pencil"></i> Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="<?= base_url('projects/delete/' . $project['projectID']) ?>" onclick="return confirm('Are you sure you want to delete this project?');">
                                                <i class="bi bi-trash"></i> Delete</a></li>
                                        </ul>
                                    </div>
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
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('/assets/js/main.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/projects.js') ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(function () {
                successMessage.classList.add('fade');
                successMessage.addEventListener('transitionend', function () {
                    successMessage.remove();
                });
            }, 3000);
        }
    });
</script>
</body>
</html>