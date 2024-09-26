<?php $pageTitle = '[Admin Page] Assign Users to Projects' ?>

<!-- Header content -->
<header>
    <?php include 'header.php' ?>
</header>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/assignusers.css')?>">
<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }
</style>
<div class="container">
    <!-- Display flash message if available -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <form action="<?php echo base_url('/projects/assign') ?>" method="post" id="userSelectionForm">
                <div class="mb-3" id="assignSelection">
                    <label for="user" class="form-label">Select User:</label>
                    <select class="form-select" name="user" id="user">
                        <option value="">Select User</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user->id; ?>"><?= $user->firstName . ' ' . $user->lastName; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3" id="projectSelection" style="display: none;">
                <label for="projects" class="form-label">Select Projects:</label>
                <select class="form-select" name="projects[]" id="projects" multiple>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?= $project['projectID'] ?>">
                            <?= $project['projectNumber'] . ' - ' . $project['projectName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">Assign User to Project(s)</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
// Show project selection when user is selected.
document.getElementById('user').addEventListener('change', function() {
    var projectSelection = document.getElementById('projectSelection');
    var selectedUserId = this.value;
    
    console.log('Selected User ID:', selectedUserId);
    
    if (selectedUserId !== '') {
        projectSelection.style.display = 'block';
        // Fetch and display unassigned projects associated with the selected user
        fetchUnassignedProjectsForUser(selectedUserId);
    } else {
        projectSelection.style.display = 'none';
    }
});

function fetchUnassignedProjectsForUser(userId) {
    console.log('Fetching unassigned projects for User ID:', userId);
    
    fetch('<?= base_url('/projects/getUnassignedProjectsForUser/') ?>' + userId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            
            const selectProjects = document.getElementById('projects');
            selectProjects.innerHTML = ''; // Clear previous options

            // Check if data.projects is defined
            if (data.projects) {
                // Add options for each project
                data.projects.forEach(project => {
                    const option = document.createElement('option');
                    option.value = project.projectID;
                    option.textContent = project.projectNumber + ' - ' + project.projectName;
                    selectProjects.appendChild(option);
                });
            } else {
                console.error('No projects found in response:', data);
            }
        })
        .catch(error => {
            console.error('Error fetching projects:', error);
        });
}

</script>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>