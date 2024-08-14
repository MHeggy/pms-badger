<?= esc($pageTitle = $user['username'] . ' timesheets') ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        th, td {
            text-align: center;
        }
    </style>

<!-- Header content -->
<header>
    <?php include 'header.php' ?>
</header>

<div class="container mt-5"><br><br>
<button onclick="goBack()" class="btn btn-primary btn-back">Back to Timesheets</button>
<br><br>
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
                <td><a href="/timesheets/view/<?= $timesheet['timesheetID'] ?>"><?= esc($timesheet['weekOf']) ?></a></td>
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
<div class="modal" tabindex="-1" id="confirmDeleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
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
</script>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
