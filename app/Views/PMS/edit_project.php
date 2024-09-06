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
            <label for="stateID" class="form-label">State</label>
            <select class="form-select" id="stateID" name="stateID" required>
                <?php foreach ($states as $state): ?>
                    <option value="<?= esc($state['stateID']) ?>" <?= $project['stateID'] == $state['stateID'] ? 'selected' : '' ?>>
                        <?= esc($state['stateName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="zipCode" class="form-label">Zip Code</label>
            <input type="text" class="form-control" id="zipCode" name="zipCode" value="<?= esc($project['zipCode']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="countryID" class="form-label">Country</label>
            <select class="form-select" id="countryID" name="countryID" required>
                <?php foreach ($countries as $country): ?>
                    <option value="<?= esc($country['countryID']) ?>" <?= $project['countryID'] == $country['countryID'] ? 'selected' : '' ?>>
                        <?= esc($country['countryName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>>
        
        <!-- Categories Section -->
        <h4>Categories</h4>
        <div class="mb-3">
            <label for="categories" class="form-label">Select Categories</label>
            <select multiple class="form-select" id="categories" name="categories[]">
                <?php foreach ($allCategories as $category): ?>
                    <option value="<?= esc($category['categoryID']) ?>">
                        <?= esc($category['categoryName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tasks Section -->
        <h4>Tasks</h4>
        <div class="mb-3">
            <label for="tasks" class="form-label">Select Tasks</label>
            <select multiple class="form-select" id="tasks" name="tasks[]">
                <?php foreach ($allTasks as $task): ?>
                    <option value="<?= esc($task['taskID']) ?>" 
                        <?= in_array($task['taskID'], array_column($selectedTasks, 'taskID')) ? 'selected' : '' ?>>
                        <?= esc($task['taskName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Update Button -->
        <button type="submit" class="btn btn-primary">Update Project</button>
    </form>
</div>

<script>
    // JavaScript to highlight previously selected categories and tasks
    document.addEventListener('DOMContentLoaded', function() {
        var selectedCategories = <?= json_encode(array_column($selectedCategories, 'categoryID')) ?>;
        var selectedTasks = <?= json_encode(array_column($selectedTasks, 'taskID')) ?>;
        
        // Highlight selected categories
        var categorySelect = document.getElementById('categories');
        for (var i = 0; i < categorySelect.options.length; i++) {
            if (selectedCategories.includes(parseInt(categorySelect.options[i].value))) {
                categorySelect.options[i].selected = true;
            }
        }

        // Highlight selected tasks
        var taskItems = document.querySelectorAll('#taskList li');
        taskItems.forEach(function(taskItem) {
            if (selectedTasks.includes(parseInt(taskItem.getAttribute('data-task-id')))) {
                taskItem.classList.add('active');
            }
        });
    });
</script>

<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>