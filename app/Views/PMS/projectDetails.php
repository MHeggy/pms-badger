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
</style>

<!-- Header content -->
<header>
    <?php include 'header.php'; ?>
</header>

<br>
<div class="container mt-4">
    <h1><?= esc($project['projectName']) ?>'s Details</h1>
    
    <!-- Go Back Button -->
    <button class="btn btn-secondary mb-4" onclick="window.history.back()">Go Back</button>

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

    <!-- Button to Open Modal for Project Updates -->
    <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#updatesModal">
        View Project Updates
    </button>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="updatesModal" tabindex="-1" aria-labelledby="updatesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatesModalLabel">Project Updates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add New Update Form -->
                <form action="<?= site_url('projects/add_update') ?>" method="post">
                    <input type="hidden" name="projectID" value="<?= esc($project['projectID']) ?>">
                    <div class="mb-3">
                        <label for="updateText" class="form-label">Add a new update</label>
                        <textarea class="form-control" id="updateText" name="updateText" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Update</button>
                </form>

                <!-- Display Updates -->
                <?php if (isset($updates) && !empty($updates)): ?>
                    <table class="table table-striped mt-4">
                        <thead class="table-dark">
                            <tr>
                                <th>User</th>
                                <th>Update</th>
                                <th>Timestamp</th>
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
