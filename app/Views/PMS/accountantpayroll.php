<?php $pageTitle = 'Payroll [Accountant]' ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<header>
    <?php include 'header.php'; ?>
</header>


<?php if (session()->getFlashdata('error_message')): ?>
        <p style="color: red;"><?= session()->getFlashdata('error_message') ?></p>
    <?php endif; ?>

    <!-- Filter Form -->
    <form method="get" action="">
        <label for="username">Username:</label>
        <select name="username" id="username">
            <option value="">Select Username</option>
            <?php foreach ($usernames as $user): ?>
                <option value="<?= $user['username']; ?>" <?= $user['username'] == $selectedUsername ? 'selected' : ''; ?>>
                    <?= $user['username']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="week">Week:</label>
        <select name="week" id="week">
            <option value="">Select Week</option>
            <?php foreach ($weeks as $week): ?>
                <option value="<?= $week['weekOf']; ?>" <?= $week['weekOf'] == $selectedWeek ? 'selected' : ''; ?>>
                    <?= $week['weekOf']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filter</button>
    </form>

    <!-- Display Filtered Timesheets -->
    <?php if (!empty($filteredTimesheets)): ?>
        <table>
            <thead>
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
                            <a href="/payroll/viewWeek/<?= $timesheet['weekOf']; ?>">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No timesheets found for the selected filters.</p>
    <?php endif; ?>
</body>
</html>
