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
                <label for="username" class="form-label">Username:</label>
                <select name="username" id="username" class="form-select">
                    <option value="">Select Username</option>
                    <?php foreach ($usernames as $user): ?>
                        <option value="<?= $user['username']; ?>" <?= $user['username'] == $selectedUsername ? 'selected' : ''; ?>>
                            <?= $user['username']; ?>
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
                        <th>Username</th>
                        <th>Week</th>
                        <th>Total Hours</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($filteredTimesheets as $timesheet): ?>
                        <tr>
                            <td><?= $timesheet['username']; ?></td>
                            <td><?= $timesheet['weekOf']; ?></td>
                            <td><?= $timesheet['totalHours']; ?></td>
                            <td>
                                <a href="/payroll/viewWeek/<?= $timesheet['weekOf']; ?>" class="btn btn-info btn-sm">View Details</a>
                                <a href="/timesheets/export/<?= $timesheet['timesheetID']; ?>" class="btn btn-success btn-sm ms-2">Export</a>
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
