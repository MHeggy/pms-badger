<?php $pageTitle = 'Payroll [Accountant]' ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<header>
    <?php include 'header.php'; ?>
</header>

<div class="container mt-5">
    <br><br>
    <h2>Payroll Overview</h2>

    <!-- Filter Form -->
    <form method="get" action="<?= base_url('/accountantpayroll') ?>" class="row mb-4">
        <div class="col-md-4">
            <label for="username" class="form-label">Filter by Username</label>
            <select name="username" id="username" class="form-select">
                <option value="">All Users</option>
                <?php foreach ($usernames as $username): ?>
                    <option value="<?= esc($username) ?>" <?= set_select('username', $username) ?>>
                        <?= esc($username) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="week" class="form-label">Filter by Week</label>
            <select name="week" id="week" class="form-select">
                <option value="">All Weeks</option>
                <?php foreach ($weeks as $week): ?>
                    <option value="<?= esc($week['weekOf']) ?>" <?= set_select('week', $week['weekOf']) ?>>
                        <?= date('Y-m-d', strtotime($week['weekOf'])) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <!-- Timesheet Results -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Username</th>
                <th>Week</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($filteredTimesheets)): ?>
                <?php foreach ($filteredTimesheets as $timesheet): ?>
                    <tr>
                        <td><?= esc($timesheet['username']) ?></td>
                        <td><?= date('Y-m-d', strtotime($timesheet['weekOf'])) ?></td>
                        <td>
                            <a href="<?= base_url('/accountantpayroll/view/' . urlencode($timesheet['id'])) ?>" class="btn btn-primary">View Timesheet</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No timesheets found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
