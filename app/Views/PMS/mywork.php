<!-- Set page title -->
<?= $pageTitle = "My Work"; ?>

<!-- Include CSS for styling -->
<link rel="stylesheet" href="<?php echo base_url('/assets/css/mywork.css') ?>">
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
<!-- Search and Filter Container -->
<div class="container" id="filter-container" style="margin-top: 20px;">
    <div class="row mb-4 search-filter-container">
        <!-- Search Form -->
        <form id="searchForm" action="<?= base_url('my_work/search') ?>" method="get" class="position-relative">
            <input type="text" id="search" name="search" class="form-control" placeholder="Search your Assigned Projects">
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
            <form id="filterForm" action="<?= base_url('my_work/filter') ?>" method="get">
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
                <div class="mb-3">
                    <label for="category" class="form-label">Project Category</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= esc($cat['categoryID']) ?>"><?= esc($cat['categoryName']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </form>
        </ul>
    </div>
</div>

<!-- Projects Table -->
<div class="container" id="project_table" style="margin-top: 20px;">
    <table class="table table-striped">
        <thead>
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
        </tr>
        </thead>
        <tbody id="project_list">
        <?php foreach ($assignedProjects as $work): ?>
            <tr data-project-id="<?= esc($work['projectID']) ?>">
                <td>
                    <a href="<?= base_url('projects/details/' . $work['projectID']) ?>">
                        <?= esc($work['projectNumber']) ?>
                    </a>
                </td>
                <td>
                    <a href="<?= base_url('projects/details/' . $work['projectID']) ?>">
                        <?= esc($work['projectName']) ?>
                    </a>
                </td>
                <td><?= esc($work['statusName']) ?></td>
                <td><?= esc(str_replace(',', ', ', $work['categoryNames'])) ?></td>
                <td><?= esc($work['dateAccepted']) ?></td>
                <td>
                    <?php if (!empty($work['assignedUsers'])): ?>
                        <ul>
                            <?php foreach (explode(',', $work['assignedUsers']) as $user): ?>
                                <li><?= esc(trim($user)) ?></li>
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

<!-- Scripts -->
<script src="<?php echo base_url('/assets/js/mywork.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/main.js') ?>"></script>
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
