<!-- Set page title -->
<?php $pageTitle = "My Work"; ?>

<!-- Header content -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

<!-- Include CSS for styling -->
<link rel="stylesheet" href="<?php echo base_url('/assets/css/mywork.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
<style>
    table thead th {
        padding: 10px; /* Add some padding for better spacing */
        text-align: left; /* Align text to the left */
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
        background-color: #dc3545; /* Red for 'Cancelled' or 'Postponed' */
        color: #212529; /* Dark text */
    }
    .project-card {
        background-color: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
</style>


<!-- Success Message Section -->
<?php if (session()->getFlashdata('success')): ?>
    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<br><br>

<!-- Projects Table with scrollable container -->
<div class="container" id="project_table" style="margin-top: 20px;">
    <!-- Card for Title and Search/Filter -->
    <div class="card project-card mb-4">
        <div class="card-body">
            <!-- Logo Section -->
            <div class="text-center mb-4">
                <div class="d-flex align-items-center justify-content-center">
                    <!-- Example icon, you can customize the icon or logo -->
                    <i class="bi bi-folder-fill" style="font-size: 50px; color: #007bff; margin-right: 10px;"></i> 
                    <h1 class="mb-0">My Assigned Projects</h1>
                </div>
            </div>

            <!-- Search and Filter Container -->
            <div class="row mb-4 search-filter-container">
                <!-- Search Form -->
                <form id="searchForm" action="<?= base_url('my_work/search') ?>" method="get" class="position-relative">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search your Assigned Projects" value="<?= esc($searchTerm ?? '') ?>">
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
                        <!-- Filter Form -->
                        <form id="filterForm" action="<?= base_url('my_work/filter') ?>" method="get">
                            <div class="mb-3">
                                <label for="status" class="form-label">Project Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Projects</option>
                                    <option value="1" <?= isset($filters['status']) && $filters['status'] == '1' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="2" <?= isset($filters['status']) && $filters['status'] == '2' ? 'selected' : '' ?>>Completed</option>
                                    <option value="3" <?= isset($filters['status']) && $filters['status'] == '3' ? 'selected' : '' ?>>Cancelled</option>
                                    <option value="4" <?= isset($filters['status']) && $filters['status'] == '4' ? 'selected' : '' ?>>Postponed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Project Category</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= esc($cat['categoryID']) ?>" <?= isset($filters['category']) && $filters['category'] == $cat['categoryID'] ? 'selected' : '' ?>>
                                            <?= esc($cat['categoryName']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                        </form>
                    </ul>
                </div>

                <!-- Active Filters Display -->
                <div id="activeFilters" class="mt-4">
                    <span id="statusFilter" class="badge bg-secondary me-2 d-none">
                        Status: <span id="statusName"></span> <i class="bi bi-x" id="clearStatus"></i>
                    </span>
                    <span id="categoryFilter" class="badge bg-secondary me-2 d-none">
                        Category: <span id="categoryName"></span> <i class="bi bi-x" id="clearCategory"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
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
                    <th>Actions</th>
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
                        <td>
                            <span class="badge <?= $work['statusName'] == 'In Progress' ? 'badge-success' : ($work['statusName'] == 'Completed' ? 'badge-primary' : 'badge-warning'); ?> rounded-pill">
                                <?= esc($work['statusName']) ?>
                            </span>
                        </td>
                        <td><?= esc(str_replace(',', ', ', $work['categoryNames'])) ?></td>
                        <td><?= esc($work['dateAccepted']) ?></td>
                        <td>
                            <?php if (!empty($work['assignedUsers']) && is_array($work['assignedUsers'])): ?>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($work['assignedUsers'] as $user): ?>
                                        <li><?= esc($user['username']) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                No users assigned.
                            <?php endif; ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-link btn-sm btn-rounded" onclick="window.location='<?= base_url('projects/edit/' . $work['projectID']) ?>'">
                                Edit
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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

    // Initialize event listeners for sorting
    document.addEventListener('DOMContentLoaded', function () {
        initializeEventListeners();
    });

    // Function to sort work based on work number
    function sortWork(order) {
        const workList = document.getElementById('project_list');
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
</body>
</html>