<?php $pageTitle = 'Payroll [Accountant]' ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/payroll.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
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
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="<?= current_url(); ?>" class="btn btn-secondary ms-2">Reset</a>
            </div>
        </form>
    </div>

    <!-- Display Filtered Timesheets -->
    <?php if (!empty($filteredTimesheets)): ?>
        <form method="post" action="/timesheets/export_multiple" class="table-responsive">
            <?= csrf_field(); ?>
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Select</th>
                        <th>Name</th>
                        <th>Total Hours</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $currentWeek = '';
                    foreach ($filteredTimesheets as $timesheet):
                        // Check if we need to start a new week section
                        if ($currentWeek !== $timesheet['weekOf']):
                            $currentWeek = $timesheet['weekOf'];
                            ?>
                            <tr class="table-info">
                                <td colspan="4"><strong>Week of <?= esc($currentWeek); ?></strong></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="timesheet_ids[]" value="<?= esc($timesheet['timesheetID']); ?>" class="timesheet-checkbox" aria-label="Select timesheet <?= esc($timesheet['timesheetID']); ?>">
                            </td>
                            <td>
                                <?php if (empty($timesheet['firstName']) && empty($timesheet['lastName'])): ?>
                                    <?= esc($timesheet['username']) ?>
                                <?php else: ?>
                                    <?= esc($timesheet['firstName'] . ' ' . $timesheet['lastName']) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($timesheet['totalHours']); ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="actionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="actionsDropdown">
                                        <li><a class="dropdown-item" href="/timesheets/view/<?= esc($timesheet['timesheetID']); ?>">View Details</a></li>
                                        <li><a class="dropdown-item" href="/timesheets/export/<?= esc($timesheet['timesheetID']); ?>">Export</a></li>
                                        <li><a class="dropdown-item" href="/timesheets/edit/<?= esc($timesheet['timesheetID']); ?>">Edit</a></li>
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= esc($timesheet['timesheetID']); ?>">Delete</a></li>
                                    </ul>
                                </div>
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
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>