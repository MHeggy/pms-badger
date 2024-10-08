<?php $pageTitle = '[Admin] Add Projects' ?>

<header>
    <?php include 'header.php' ?>
</header>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Custom CSS -->
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">



<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header text-center">
                    <h3 class="card-title">Add New Project</h3>
                </div>
                <div class="card-body">
                    <form id="addProjectForm" action="<?php echo base_url('/projects/add') ?>" method="post">
                        <!-- Address Details -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Project Address</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="street" class="form-label">Street</label>
                                    <input type="text" class="form-control" id="street" name="street" placeholder="Enter Street" required>
                                </div>
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" required>
                                </div>
                                <div class="mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <select class="form-select" id="state" name="stateID" required>
                                        <?php foreach ($states as $state): ?>
                                            <option value="<?php echo $state['stateID']; ?>"><?php echo $state['stateName']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="zipCode" class="form-label">Zip Code</label>
                                    <input type="text" class="form-control" id="zipCode" name="zipCode" placeholder="Enter Zip Code" required>
                                </div>
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <select class="form-select" id="country" name="countryID" required>
                                        <?php foreach ($countries as $country): ?>
                                            <option value="<?php echo $country['countryID']; ?>" <?php echo ($country['countryID'] == 8) ? 'selected' : ''; ?>>
                                                <?php echo $country['countryName']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Project Details -->
                        <div class="mb-3">
                            <label for="project_name" class="form-label">Project Name</label>
                            <input type="text" class="form-control" id="project_name" name="project_name" placeholder="Enter Project Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="project_description" class="form-label">Project Description</label>
                            <textarea class="form-control" id="project_description" name="project_description" placeholder="Enter Project Description" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="date_accepted" class="form-label">Date Accepted</label>
                            <input type="date" class="form-control" id="date_accepted" name="date_accepted" required>
                        </div>
                        <div class="mb-3">
                            <label for="project_number" class="form-label">Project Number:</label>
                            <input type="text" class="form-control" id="project_number" name="project_number" required placeholder="Enter Project Number">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1">In Progress</option>
                                <option value="2">Completed</option>
                                <option value="3">Canceled</option>
                                <option value="4">Postponed</option>
                            </select>
                        </div>

                        <!-- Category Details -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">Project Categories</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="categories" class="form-label">Categories</label>
                                    <select class="form-select" id="categories" name="categories[]" multiple required>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['categoryID']; ?>"><?php echo $category['categoryName']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Task Details -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">Project Tasks</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="tasks" class="form-label">Tasks</label>
                                    <select class="form-select" id="tasks" name="tasks[]" multiple required>
                                        <?php foreach ($tasks as $task): ?>
                                            <option value="<?php echo $task['taskID']; ?>"><?php echo $task['taskName']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- Dynamic Deadline Inputs -->
                                <div id="task-deadlines"></div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> Add Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
<script>
    $(document).ready(function() {
    $('#tasks').on('change', function() {
        let selectedTasks = $(this).val();
        $('#task-deadlines').empty();
        
        selectedTasks.forEach(function(taskID) {
            // Get the task name by selecting the option text based on the taskID
            let taskName = $('#tasks option[value="'+taskID+'"]').text();
            
            // Append the input field with the dynamic task name
            $('#task-deadlines').append(`
                <div class="mb-3">
                    <label for="deadline-${taskID}" class="form-label">Deadline for ${taskName}</label>
                    <input type="date" class="form-control" id="deadline-${taskID}" name="deadlines[${taskID}]">
                </div>
            `);
        });
    });
});
</script>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</body>
</html>