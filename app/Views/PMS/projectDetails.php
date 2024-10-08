<?php $pageTitle = 'Project Details' ?>

<!-- Header content -->
<header>  
    <?php include 'header.php'; ?>
</header>

<link rel="stylesheet" href="<?= base_url('/assets/css/projectDetails.css') ?>">
<link rel="stylesheet" href="<?= base_url('/assets/css/main.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for icons -->

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }
</style>
<br>

<!-- Main content -->
<div class="container mt-4">
    <h1><?= esc($project['projectName']) ?>'s Details</h1>
    
    <!-- Success Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= esc($error) ?>
        </div>
    <?php endif; ?>

    <!-- Button Row -->
    <div class="d-flex justify-content-between mb-4">
        <!-- Go Back Button -->
        <button class="btn btn-secondary" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i> Go Back
        </button>
        <!-- Button to Open Modal for Adding Update -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUpdateModal">
            <i class="fas fa-plus"></i> Add Update
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
                <th>Project Description</th>
                <td>
                    <p><?= esc($project['projectDescription']) ?></p>
                </td>
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
                        <?= implode(', ', array_column($project['categories'], 'categoryName')) ?>
                    <?php else: ?>
                        None
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Tasks</th>
                <td>
                    <?php if (!empty($project['tasks'])): ?>
                        <ul>
                            <?php foreach ($project['tasks'] as $task): ?>
                                <li>
                                    <?= esc($task['taskName']) ?>
                                    <?php if (!empty($task['deadline'])): ?>
                                        (Deadline: <?= esc(date('n/j/Y', strtotime($task['deadline']))) ?>)
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($updates as $update): ?>
                        <tr>
                            <td><?= esc($update['username']) ?></td>
                            <td><?= esc($update['updateText']) ?></td>
                            <td><?= esc(date('n/j/Y \@ g:ia', strtotime($update['timestamp']))) ?></td>
                            <td>
                                <?php if ($update['userID'] === auth()->id() || auth()->user()->inGroup('superadmin')): ?>
                                    <!-- Dropdown Menu for Actions -->
                                    <div class="d-flex">
                                        <!-- Edit Button -->
                                        <a class="btn btn-light btn-sm me-2" href="#" data-bs-toggle="modal" data-bs-target="#editUpdateModal-<?= $update['updateID'] ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <!-- Delete Button -->
                                        <a class="btn btn-light btn-sm text-danger" href="<?= base_url('projects/delete_update/' . $update['updateID']) ?>" onclick="return confirm('Are you sure you want to delete this update?');">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </td>
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

    <!-- Modal Structure for Editing Update -->
    <?php foreach ($updates as $update): ?>
    <div class="modal fade" id="editUpdateModal-<?= $update['updateID'] ?>" tabindex="-1" aria-labelledby="editUpdateModalLabel-<?= $update['updateID'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUpdateModalLabel-<?= $update['updateID'] ?>">Edit Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('/projects/edit_update') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="updateID" value="<?= esc($update['updateID']) ?>">
                        <input type="hidden" name="projectID" value="<?= esc($project['projectID']) ?>">
                        <div class="mb-3">
                            <label for="updateText-<?= $update['updateID'] ?>" class="form-label">Type Here</label>
                            <textarea class="form-control" id="updateText-<?= $update['updateID'] ?>" name="updateText" rows="3" required><?= esc($update['updateText']) ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

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
                        <?= csrf_field() ?>
                        <input type="hidden" name="projectID" value="<?= esc($project['projectID']) ?>">
                        <div class="mb-3">
                            <label for="updateText" class="form-label">Type Here</label>
                            <textarea class="form-control" id="updateText" name="updateText" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Submit Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure for Deleting Update -->
<?php foreach ($updates as $update): ?>
<div class="modal fade" id="deleteUpdateModal-<?= $update['updateID'] ?>" tabindex="-1" aria-labelledby="deleteUpdateModalLabel-<?= $update['updateID'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUpdateModalLabel-<?= $update['updateID'] ?>">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this update?
            </div>
            <div class="modal-footer">
                <form action="<?= base_url('projects/delete_update/' . $update['updateID']) ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- JavaScript files -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
<script>
    // Example for checking modal events
    var myModal = document.getElementById('addUpdateModal')
    myModal.addEventListener('show.bs.modal', function (event) {
    console.log('Modal is opening');
    })

    myModal.addEventListener('hidden.bs.modal', function (event) {
    console.log('Modal is closed');
    })

</script>