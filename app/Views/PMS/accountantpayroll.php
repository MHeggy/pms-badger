<?php $pageTitle = 'Payroll [Accountant]' ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<header>
    <?php include 'header.php'; ?>
</header>
<br><br>
<div class="container mt-5">
    <?php if (session()->getFlashdata('error_message')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error_message') ?>
        </div>
    <?php endif; ?>

    <!-- Filter Form -->
    <div class="card p-4 mb-4">
        <form method="get" action="" class="row g-3">
            <div class="col-md-6">
                <label for="userID" class="form-label">User Name:</label>
                <select name="userID" id="userID" class="form-select">
                    <option value="">Select User</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id']; ?>" <?= $user['id'] == $selectedUserId ? 'selected' : ''; ?>>
                            <?= esc($user['firstName'] . ' ' . $user['lastName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="week" class="form-label">Week:</label>
                <select name="week" id="week" class="form-select">
                    <option value="">Select Week</option>
                    <?php foreach ($weeks as $week): ?>
                        <option value="<?= $week['weekOf']; ?>" <?= $week['weekOf'] == $selectedWeek ? 'selected' : ''; ?>>
                            <?= $week['weekOf']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Reset and Filter buttons -->
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="<?= current_url(); ?>" class="btn btn-secondary ms-2">Reset</a>
            </div>
        </form>
    </div>

    <!-- Display Filtered Timesheets -->
    <?php if (!empty($filteredTimesheets)): ?>
        <form method="post" action="/timesheets/export_multiple" class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Select</th>
                        <th>Name</th>
                        <th>Week</th>
                        <th>Total Hours</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($filteredTimesheets as $timesheet): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="timesheet_ids[]" value="<?= esc($timesheet['timesheetID']); ?>" class="timesheet-checkbox">
                            </td>
                            <td><?= esc($timesheet['firstName'] . ' ' . $timesheet['lastName']) ?></td>
                            <td><?= esc($timesheet['weekOf']); ?></td>
                            <td><?= esc($timesheet['totalHours']); ?></td>
                            <td>
                                <a href="/timesheets/view/<?= esc($timesheet['timesheetID']); ?>" class="btn btn-info btn-sm">View Details</a>
                                <a href="/timesheets/export/<?= esc($timesheet['timesheetID']); ?>" class="btn btn-success btn-sm ms-2">Export</a>
                                <a href="/timesheets/edit/<?= esc($timesheet['timesheetID']); ?>" class="btn btn-warning btn-sm ms-2">Edit</a>
                                <a href="#" class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= esc($timesheet['timesheetID']); ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-success" id="exportButton" style="display: none;">Export <span id="exportCount">0</span> Timesheet(s)</button>
            </div>
        </form>
    <?php else: ?>
        <p class="text-center mt-4">No timesheets found for the selected filters.</p>
    <?php endif; ?>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this timesheet? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="post" id="deleteForm">
                    <input type="hidden" name="timesheetID" id="deleteTimesheetID">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.timesheet-checkbox');
        const exportButton = document.getElementById('exportButton');
        const exportCount = document.getElementById('exportCount');

        function updateExportButton() {
            const checkedCount = document.querySelectorAll('.timesheet-checkbox:checked').length;
            exportCount.textContent = checkedCount;

            if (checkedCount > 0) {
                exportButton.style.display = 'block';
            } else {
                exportButton.style.display = 'none';
            }
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateExportButton);
        });

        // Initial check in case of pre-selected checkboxes.
        updateExportButton();

        // Handle Delete Modal
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const timesheetId = button.getAttribute('data-id');
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = '/timesheets/delete/' + timesheetId;
            document.getElementById('deleteTimesheetID').value = timesheetId;
        });
    });
</script>
