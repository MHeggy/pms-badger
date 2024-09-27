<?php esc($pageTitle = $user['username'] . ' Timesheets') ?>

<header>
    <?php include 'header.php'; ?>
</header>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Roboto', sans-serif;
    }
    th, td {
        text-align: center;
    }
    .btn-back {
        margin-bottom: 20px;
    }
    .table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .table thead {
        background-color: #007bff;
        color: white;
    }
    .modal-content {
        border-radius: 8px;
    }
    .container {
        margin-top: 40px;
    }
</style>

<div class="container">
<?php if (session()->get('success_message') || !empty($errorMessage)): ?>
        <div class="container">
            <?php if (session()->get('success_message')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->get('success_message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $errorMessage ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <h2 class="text-center mb-4"><?= esc($user->firstName) . ' ' . esc($user->lastName) ?>'s Timesheets</h2> <!-- Added title -->

    <button onclick="goBack()" class="btn btn-primary btn-back">
        <i class="bi bi-arrow-left"></i> Go Back
    </button>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Week Of</th>
                <th scope="col">Hours Worked</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($timesheets as $timesheet): ?>
                <tr>
                    <td><a href="/timesheets/view/<?= $timesheet['timesheetID'] ?>" class="text-decoration-none"><?= esc($timesheet['weekOf']) ?></a></td>
                    <td><?= esc($timesheet['totalHours']) ?></td>
                    <td>
                        <a href="/timesheets/edit/<?= $timesheet['timesheetID'] ?>" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button class="btn btn-danger" data-backdrop="false" onclick="confirmDelete(<?= $timesheet['timesheetID'] ?>)">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" tabindex="-1" id="confirmDeleteModal" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this timesheet?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
                <a href="#" id="confirmDeleteButton" class="btn btn-danger">
                    <i class="bi bi-check-circle"></i> Delete
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmDelete(timesheetId) {
        var deleteButton = document.getElementById('confirmDeleteButton');
        deleteButton.setAttribute('href', '/timesheets/delete/' + timesheetId);
        var myModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        myModal.show();
    }

    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>