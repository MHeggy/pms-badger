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
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>

    <!-- Display Filtered Timesheets -->
    <?php if (!empty($filteredTimesheets)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Week</th>
                        <th>Total Hours</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($filteredTimesheets as $timesheet): ?>
                        <tr>
                            <td><?= esc($timesheet['firstName'] . ' ' . $timesheet['lastName']) ?></td>
                            <td><?= esc($timesheet['weekOf']); ?></td>
                            <td><?= esc($timesheet['totalHours']); ?></td>
                            <td>
                                <a href="/timesheets/view/<?= esc($timesheet['timesheetID']); ?>" class="btn btn-info btn-sm">View Details</a>
                                <a href="/timesheets/export/<?= esc($timesheet['timesheetID']); ?>" class="btn btn-success btn-sm ms-2">Export</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center mt-4">No timesheets found for the selected filters.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
