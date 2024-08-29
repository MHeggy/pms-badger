<?= $pageTitle = 'Project Details' ?>
<link rel="stylesheet" href="<?= base_url('/assets/css/projectDetails.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .table-dark th {
        background-color: #343a40;
        color: #ffffff;
        font-weight: bold;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .table-striped tbody tr:nth-of-type(even) {
        background-color: #ffffff;
    }

    .modal-xl-custom {
        max-width: 90%;
        width: 90%;
    }
</style>

<!-- Header content -->
<header>
    <?php include 'header.php'; ?>
</header>
<br>

<!-- Main content -->
<div class="container mt-4">
    <h1><?= esc($project['projectName']) ?>'s Details</h1>
    
    <!-- Button Row -->
    <div class="d-flex justify-content-between mb-4">
        <!-- Go Back Button -->
        <button class="btn btn-secondary" onclick="window.history.back()">Go Back</button>
        <!-- Button to Open Modal for Adding Update -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUpdateModal">
            Add Update
        </button>
    </div>

    <!-- Project Details Table -->
    <table class="table">
        <tbody>
            <tr>
                <th>Project Number</th>
                <td><?= esc($project['projectNumber']) ?></td>
            </tr>
            <tr>
                <th>Project Name</th>
                <td><?= esc($project['projectName']) ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= esc($project['statusName']) ?></td>
            </tr>
            <tr>
                <th>Date Accepted</th>
                <td><?= esc($project['dateAccepted']) ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td>
                    <?= esc($project['street']) ?>, <?= esc($project['city']) ?>,
                    <?= esc($project['stateName']) ?> | <?= esc($project['zipCode']) ?><br>
                    <?= esc($project['countryName']) ?>
                </td>
            </tr>
            <tr>
                <th>Categories</th>
                <td>
                    <?php if (!empty($project['categories'])): ?>
                        <?= implode(', ', $project['categories']) ?>
                    <?php else: ?>
                        None
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Tasks</th>
                <td>
                    <?php if (!empty($project['tasks'])): ?>
                        <?= implode(', ', $project['tasks']) ?>
                    <?php else: ?>
                        None
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Display Updates -->
    <div class="mt-4">
        <h2>Project Updates</h2>
        <?php if (isset($updates) && !empty($updates)): ?>
            <table class="table table-striped mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>User</th>
                        <th>Update</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($updates as $update): ?>
                        <tr>
                            <td><?= esc($update['username']) ?></td>
                            <td><?= esc($update['updateText']) ?></td>
                            <td><?= esc(date('n/j/Y \@ g:ia', strtotime($update['timestamp']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info mt-4" role="alert">
                No updates available for this project.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Structure for Adding Update -->
<div class="modal fade" id="addUpdateModal" tabindex="-1" aria-labelledby="addUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUpdateModalLabel">Add a New Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('projects/add_update') ?>" method="post">
                    <input type="hidden" name="projectID" value="<?= esc($project['projectID']) ?>">
                    <div class="mb-3">
                        <label for="updateText" class="form-label">Type Here</label>
                        <textarea class="form-control" id="updateText" name="updateText" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>