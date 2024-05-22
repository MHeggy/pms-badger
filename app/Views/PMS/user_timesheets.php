<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= esc($pageTitle = $user['username'] . ' timesheets') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        th, td {
            text-align: center;
        }
    </style>
</head>
<body>
<header>
    <?php include 'header.php' ?>
</header>

<div class="container mt-5"><br><br>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Week Of</th>
            <th scope="col">Hours Worked</th>
            <th scope="col">Pay Rate</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($timesheets as $timesheet): ?>
            <tr>
                <td><?= $timesheet['week_of'] ?></td>
                <td><?= getTotalHoursWorked($timesheet['id']) ?></td>
                <td><?= $user->pay_rate ?></td>
                <td>
                    <a href="/timesheets/edit/<?= $timesheet['id'] ?>" class="btn btn-primary">Edit</a>
                    <button class="btn btn-danger" onclick="confirmDelete(<?= $timesheet['id'] ?>)">Delete</button>
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

<?php
function getTotalHoursWorked($timesheetId)
{
$db = \Config\Database::connect();
$query = $db->table('daily_hours')->select('monday, tuesday, wednesday, thursday, friday, saturday, sunday')->where('timesheet_id', $timesheetId)->get()->getRow();
$totalHours = 0;

// Sum up hours from each day of the week column
$totalHours += $query->monday;
$totalHours += $query->tuesday;
$totalHours += $query->wednesday;
$totalHours += $query->thursday;
$totalHours += $query->friday;
$totalHours += $query->saturday;
$totalHours += $query->sunday;

return $totalHours;
}
?>

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
</script>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
