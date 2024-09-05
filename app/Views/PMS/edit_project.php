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

<br><br><br>
<div class="container" style="margin-top: 20px;">
    <h2>Edit Project</h2>
    <form action="<?= base_url('projects/update') ?>" method="post">
        <input type="hidden" name="projectID" value="<?= esc($project['projectID']) ?>">
        
        <!-- Project Name -->
        <div class="mb-3">
            <label for="projectName" class="form-label">Project Name</label>
            <input type="text" class="form-control" id="projectName" name="projectName" value="<?= esc($project['projectName']) ?>" required>
        </div>
        
        <!-- Project Number -->
        <div class="mb-3">
            <label for="projectNumber" class="form-label">Project Number</label>
            <input type="text" class="form-control" id="projectNumber" name="projectNumber" value="<?= esc($project['projectNumber']) ?>" required>
        </div>
        
        <!-- Project Status -->
        <div class="mb-3">
            <label for="statusID" class="form-label">Status</label>
            <select class="form-select" id="statusID" name="statusID" required>
                <option value="1" <?= $project['statusID'] == 1 ? 'selected' : '' ?>>In Progress</option>
                <option value="2" <?= $project['statusID'] == 2 ? 'selected' : '' ?>>Completed</option>
                <option value="3" <?= $project['statusID'] == 3 ? 'selected' : '' ?>>Cancelled</option>
                <option value="4" <?= $project['statusID'] == 4 ? 'selected' : '' ?>>Postponed</option>
            </select>
        </div>
        
        <!-- Date Accepted -->
        <div class="mb-3">
            <label for="dateAccepted" class="form-label">Date Accepted</label>
            <input type="date" class="form-control" id="dateAccepted" name="dateAccepted" value="<?= esc($project['dateAccepted']) ?>" required>
        </div>

        <!-- Address Fields -->
        <h4>Address</h4>
        <div class="mb-3">
            <label for="street" class="form-label">Street</label>
            <input type="text" class="form-control" id="street" name="street" value="<?= esc($project['street']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" class="form-control" id="city" name="city" value="<?= esc($project['city']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="stateName" class="form-label">State</label>
            <input type="text" class="form-control" id="stateName" name="stateName" value="<?= esc($project['stateName']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="zipCode" class="form-label">Zip Code</label>
            <input type="text" class="form-control" id="zipCode" name="zipCode" value="<?= esc($project['zipCode']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="countryName" class="form-label">Country</label>
            <input type="text" class="form-control" id="countryName" name="countryName" value="<?= esc($project['countryName']) ?>" required>
        </div>
        
        <!-- Categories Section -->
        <h4>Categories</h4>
        <div class="mb-3">
            <label for="categories" class="form-label">Select Categories</label>
            <select multiple class="form-select" id="categories" name="categories[]">
                <?php foreach ($allCategories as $category): ?>
                    <option value="<?= esc($category['categoryID']) ?>" <?= in_array($category['categoryID'], array_column($selectedCategories, 'categoryID')) ? 'selected' : '' ?>>
                        <?= esc($category['categoryName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tasks Section -->
        <h4>Tasks</h4>
        <div class="mb-3">
            <label for="tasks" class="form-label">Tasks</label>
            <ul class="list-group">
                <?php foreach ($allTasks as $task): ?>
                    <li class="list-group-item <?= in_array($task['taskID'], array_column($selectedTasks, 'taskID')) ? 'active' : '' ?>">
                        <?= esc($task['taskName']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>


        <!-- Update Button -->
        <button type="submit" class="btn btn-primary">Update Project</button>
    </form>
</div>

<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>