<?php esc($pageTitle = $user['username'] . ' Timesheets') ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

<!-- Header content -->
<header>
    <?php include 'header.php'; ?>
</header>

<div class="container">
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
                        <a href="/timesheets/edit/<?= $timesheet['timesheetID'] ?>" class="btn btn-primary">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete(<?= $timesheet['timesheetID'] ?>)">Delete</button>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteButton" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>



<!-- Script to handle confirmation modal -->
<script>
    function confirmDelete(timesheetId) {
        // Set the href attribute of the delete button in the modal
        var deleteButton = document.getElementById('confirmDeleteButton');
        deleteButton.setAttribute('href', '/timesheets/delete/' + timesheetId);
        // Show the modal
        var myModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        myModal.show();
    }

    function goBack() {
        window.history.back();
    }

    $('#confirmDeleteModal').prependTo('body');
</script>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>