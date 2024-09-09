<?= $pageTitle = "My Work"; ?>
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
                        <h5 class="card-title">Search My Work</h5>
                        <form id="searchForm" action="<?= base_url('my_work/search') ?>" method="get">
                            <input type="text" id="search" name="search" class="form-control" placeholder="Search by name">
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
                        <h5 class="card-title">Filter Work</h5>
                        <form id="filterForm" action="<?= base_url('my_work/filter') ?>" method="get">
                            <select name="status" id="status" class="form-select">
                                <option value="">All Work</option>
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

<!-- Work Table -->
<div class="container" id="work_table" style="margin-top: 20px;">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    Work Number
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-primary" onclick="sortWork('asc')">
                            <i class="bi bi-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" onclick="sortWork('desc')">
                            <i class="bi bi-arrow-down"></i>
                        </button>
                    </div>
                </th>
                <th>Work Name</th>
                <th>Work Status</th>
                <th>Category</th> <!-- New Column -->
                <th>Date Accepted</th>
                <th>Assigned Users</th>
            </tr>
        </thead>
        <tbody id="work_list">
        <?php foreach ($assignedWork as $work): ?>
            <tr data-work-id="<?= $work['workID'] ?>">
                <td>
                    <a href="<?= base_url('work/details/' . $work['workID']) ?>">
                        <?= esc($work['workNumber']) ?>
                    </a>
                </td>
                <td>
                    <a href="<?= base_url('work/details/' . $work['workID']) ?>">
                        <?= esc($work['workName']) ?>
                    </a>
                </td>
                <td><?= esc($work['statusName']) ?></td>
                <td><?= esc(str_replace(',', ', ', $work['categoryNames'])) ?></td>
                <td><?= esc($work['dateAccepted']) ?></td>
                <td>
                    <?php if (!empty($work['assignedUsers'])): ?>
                        <?php
                            $users = is_string($work['assignedUsers']) ? explode(',', $work['assignedUsers']) : [];
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
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Scripts -->
<script src="<?php echo base_url('/assets/js/mywork.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/main.js') ?>"></script>
</body>
</html>