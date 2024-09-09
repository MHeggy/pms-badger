<!-- Set page title -->
<?= $pageTitle = "My Work"; ?>

<!-- Include CSS for styling -->
<link rel="stylesheet" href="<?php echo base_url('/assets/css/projects.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">

<!-- Header content -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

<!-- Search and Filter -->
<div class="container" id="filter-container" style="margin-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="search-filter-container">
                <div class="card search-filter-card">
                    <div class="card-body">
                        <h5 class="card-title">Search Projects</h5>
                        <form id="searchForm" action="<?= base_url('my_work/search') ?>" method="get">
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
                        <form id="filterForm" action="<?= base_url('my_work/filter') ?>" method="get">
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

<!-- Projects Table -->
<div class="container" id="project_table" style="margin-top: 20px;">
    <table class="table table-striped">
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
        <tbody id="project_list">
        <?php foreach ($assignedWork as $work): ?>
            <tr data-project-id="<?= $work['projectID'] ?>">
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
<script src="<?php echo base_url('/assets/js/mywork.js')?>"></script>
<script src="<?php echo base_url('/assets/js/main.js') ?>"></script>
</body>
</html>