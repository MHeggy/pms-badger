<!-- Set page title -->
<?php $pageTitle = "My Work"; ?>

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
            <input type="text" id="search" name="searchTerm" class="form-control" placeholder="Search your Assigned Projects" value="<?= esc($filters['searchTerm'] ?? '') ?>">
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
                            <option value="1" <?= (isset($filters['status']) && $filters['status'] == 1) ? 'selected' : '' ?>>In Progress</option>
                            <option value="2" <?= (isset($filters['status']) && $filters['status'] == 2) ? 'selected' : '' ?>>Completed</option>
                            <option value="3" <?= (isset($filters['status']) && $filters['status'] == 3) ? 'selected' : '' ?>>Cancelled</option>
                            <option value="4" <?= (isset($filters['status']) && $filters['status'] == 4) ? 'selected' : '' ?>>Postponed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Project Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat['categoryID']) ?>" <?= (isset($filters['category']) && $filters['category'] == $cat['categoryID']) ? 'selected' : '' ?>><?= esc($cat['categoryName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </form>
            </ul>
        </div>
    </div>

    <!-- Projects Table with scrollable container -->
    <div class="container" id="project_table" style="margin-top: 20px;">
        <div class="table-responsive">
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
                                <?php if (!empty($work['assignedUsers']) && is_array($work['assignedUsers'])): ?>
                                    <ul>
                                        <?php foreach ($work['assignedUsers'] as $user): ?>
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
    </div>
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
    
    document.addEventListener('DOMContentLoaded', function () {
        initializeEventListeners();
    });

    // Function to sort work based on work number
    function sortWork(order) {
        const workList = document.getElementById('project_list'); // Updated ID
        const rows = Array.from(workList.querySelectorAll('tr'));

        const sortedRows = rows.sort((a, b) => {
            const aParts = a.cells[0].textContent.trim().split('-');
            const bParts = b.cells[0].textContent.trim().split('-');

            const aYear = parseInt(aParts[0]);
            const bYear = parseInt(bParts[0]);
            const aNumber = parseInt(aParts[1]);
            const bNumber = parseInt(bParts[1]);

            if (aYear !== bYear) {
                return order === 'asc' ? aYear - bYear : bYear - aYear;
            } else {
                return order === 'asc' ? aNumber - bNumber : bNumber - aNumber;
            }
        });

        workList.innerHTML = '';
        sortedRows.forEach(row => workList.appendChild(row));
    }

    // Initialize event listeners for sorting
    function initializeEventListeners() {
        const sortAsc = document.getElementById('sortAsc');
        const sortDesc = document.getElementById('sortDesc');

        sortAsc.addEventListener('click', () => sortWork('asc'));
        sortDesc.addEventListener('click', () => sortWork('desc'));
    }
</script>