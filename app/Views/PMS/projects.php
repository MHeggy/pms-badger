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
        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<br><br><br>

<div class="container" id="project_table" style="margin-top: 20px;">
    <!-- Search and Filter Container Above the Table -->
    <div class="row mb-4 search-filter-container">
    <!-- Search Form -->
    <form id="searchForm" action="<?= base_url('projects/search') ?>" method="get" class="position-relative">
        <input type="text" id="search" name="search" class="form-control" placeholder="Search Projects" value="<?= esc($searchTerm ?? '') ?>">
        <button type="submit" id="searchButton" class="btn">
            <i class="bi bi-search"></i>
        </button>
        <button type="button" id="clearSearchButton" class="btn btn-clear">
            <i class="bi bi-x-circle"></i>
        </button>
    </form>

    <!-- Filter Dropdown -->
<div class="dropdown">
    <button id="filterToggle" class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        <i class="bi bi-filter"></i> Filter
    </button>
    <ul id="filterOptions" class="dropdown-menu p-3 collapse">
        <form id="filterForm" action="<?= base_url('projects/filter') ?>" method="get">
            <!-- Filter status -->
            <div class="mb-3">
                <label for="status" class="form-label">Project Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Projects</option>
                    <option value="1">In Progress</option>
                    <option value="2">Completed</option>
                    <option value="3">Cancelled</option>
                    <option value="4">Postponed</option>
                </select>
            </div>
            <!-- Filter category -->
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select name="category" id="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['categoryID'] ?>"><?= $cat['categoryName'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Add Button back here -->
            <button type="submit" class="btn btn-primary">Apply Filters</button> <!-- This is the button being added back -->
        </form>
    </ul>
</div>

<!-- Filter Display (hidden initially) -->
<div id="activeFilters" class="d-none">
    <span id="statusFilter" class="badge bg-secondary me-2 d-none">
        Status: <span id="statusName"></span> <i class="bi bi-x" id="clearStatus"></i>
    </span>
    <span id="categoryFilter" class="badge bg-secondary me-2 d-none">
        Category: <span id="categoryName"></span> <i class="bi bi-x" id="clearCategory"></i>
    </span>
</div>

    <!-- Table for Projects -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Project Number
                    <span id="sortArrow" class="sort-arrow">
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
                    <th></th>
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
                        <td><?= esc($project['statusName']) ?></td>
                        <td><?= esc(str_replace(',', ', ', $project['categoryNames'])) ?></td>
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
                        <?php if ($user1->inGroup('superadmin')): ?>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton-<?= $project['projectID'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-<?= $project['projectID'] ?>">
                                    <li><a class="dropdown-item" href="<?= base_url('projects/edit/' . $project['projectID']) ?>">Edit</a></li>
                                    <li><a class="dropdown-item text-danger" href="<?= base_url('projects/delete/' . $project['projectID']) ?>" onclick="return confirm('Are you sure you want to delete this project?');">Delete</a></li>
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