<?= $pageTitle = 'Project Details' ?>
<link rel="stylesheet" href="<?= base_url('/assets/css/projectDetails.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Header content -->
<header>
    <?php include 'header.php'; ?>
</header>

<br><br><br><br><br>
<div class="container mt-4">
    <h1><?= $project['projectName'] ?>'s Details</h1>
    
    <!-- Go Back Button -->
    <button class="btn btn-secondary mb-4" onclick="window.history.back()">Go Back</button>

    <table class="table">
        <tbody>
            <tr>
                <th>Project Number</th>
                <td><?= esc($project['projectNumber']) ?></td>
            </tr>
            <tr>
                <th>Project Name</th>
                <td><?= esc($project['projectName']) ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= esc($project['statusName']) ?></td>
            </tr>
            <tr>
                <th>Date Accepted</th>
                <td><?= esc($project['dateAccepted']) ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td>
                    <?= esc($project['street']) ?>, <?= esc($project['city']) ?>,
                    <?= esc($project['stateName']) ?> | <?= esc($project['zipCode']) ?><br>
                    <?= esc($project['countryName']) ?>
                </td>
            </tr>
            <tr>
                <th>Categories</th>
                <td>
                    <?php if (!empty($project['categories'])): ?>
                        <?= implode(', ', $project['categories']) ?>
                    <?php else: ?>
                        None
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Tasks</th>
                <td>
                    <?php if (!empty($project['tasks'])): ?>
                        <?= implode(', ', $project['tasks']) ?>
                    <?php else: ?>
                        None
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
