<?= $pageTitle = "Edit Project"; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/edit_project.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/projects.css') ?>">
<!-- Header content -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

<br><br><br><br><br>
<div class="container" style="margin-top: 20px;">
    <h2>Edit Project</h2>
    <form action="<?= base_url('projects/update') ?>" method="post">
        <input type="hidden" name="projectID" value="<?= esc($project['projectID']) ?>">
        
        <div class="mb-3">
            <label for="projectName" class="form-label">Project Name</label>
            <input type="text" class="form-control" id="projectName" name="projectName" value="<?= esc($project['projectName']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="projectNumber" class="form-label">Project Number</label>
            <input type="text" class="form-control" id="projectNumber" name="projectNumber" value="<?= esc($project['projectNumber']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="statusID" class="form-label">Status</label>
            <select class="form-select" id="statusID" name="statusID" required>
                <option value="1" <?= $project['statusID'] == 1 ? 'selected' : '' ?>>In Progress</option>
                <option value="2" <?= $project['statusID'] == 2 ? 'selected' : '' ?>>Completed</option>
                <option value="3" <?= $project['statusID'] == 3 ? 'selected' : '' ?>>Cancelled</option>
                <option value="4" <?= $project['statusID'] == 4 ? 'selected' : '' ?>>Postponed</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="dateAccepted" class="form-label">Date Accepted</label>
            <input type="date" class="form-control" id="dateAccepted" name="dateAccepted" value="<?= esc($project['dateAccepted']) ?>" required>
        </div>
        
        <!-- Add additional fields as needed -->

        <button type="submit" class="btn btn-primary">Update Project</button>
    </form>
</div>

<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>