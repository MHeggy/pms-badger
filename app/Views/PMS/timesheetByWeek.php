<!DOCTYPE html>
<html>
<head>
    <title><?php $pageTitle = 'Timesheets'?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <div class="container mt-5">
        <h2>Timesheets for Week Of: <?= date('Y-m-d', strtotime($weekOf)) ?></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Total Hours</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($timesheets)): ?>
                    <?php foreach ($timesheets as $timesheet): ?>
                        <tr>
                            <td><?= $timesheet['userID'] // Replace with user's name if available ?></td>
                            <td><?= $timesheet['totalHours'] ?></td>
                            <td>
                                <a href="<?= base_url('/timesheets/view' . $timesheet['timesheetID']) ?>" class="btn btn-info">View Details</a>
                            </td>
                            <td>
                                <a href="<?= site_url('timesheets/export/' . $timesheet['timesheetID']); ?>" class="btn btn-success">Export to Excel</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No timesheets found for this week.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>