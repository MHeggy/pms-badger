<?php $pageTitle = 'Payroll [Accountant]' ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <header>
        <?php include 'header.php'; ?>
    </header>

    <div class="container mt-5">
        <br><br>
        <h2>Payroll Overview</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Week</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($weeks)): ?>
                    <?php foreach ($weeks as $week): ?>
                        <tr>
                            <td><?= date('Y-m-d', strtotime($week['weekOf'])) ?></td>
                            <td>
                                <a href="<?= base_url('/accountantpayroll/week/' . urlencode($week['weekOf'])) ?>" class="btn btn-primary">View Timesheets</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">No timesheets found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
