<?= $pageTitle = '[Admin Page] Unassign Users to Projects' ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/unassignusers.css')?>">

<!-- Header content -->
<header>
    <?php include 'header.php' ?>
</header>
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
                    <form action="<?php echo base_url('/projects/unassign') ?>" method="post" id="userSelectionForm">
                        <div class="mb-3" id="unassignSelection">
                            <label for="user" class="form-label">Select User:</label>
                            <select class="form-select" name="unassign_user" id="unassign_user">
                                <option value="">Select User</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user->id; ?>"><?= $user->username; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3" id="projectSelection" style="display: none;">
                        <label for="projects" class="form-label">Select Projects:</label>
                        <select class="form-select" name="unassign_projects[]" id="unassign_projects" multiple>
                            <?php foreach ($projects as $project): ?>
                                <option value="<?= $project['projectID'] ?>"><?= $project['projectName'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Unassign User from Project(s)</button> <!-- Change class to btn-primary -->
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Show project selection when user is selected.
            document.getElementById('unassign_user').addEventListener('change', function() {
                var projectSelection = document.getElementById('projectSelection');
                if (this.value !== '') {
                    projectSelection.style.display = 'block';

                    // Fetch and display projects associated with the selected user
                    fetchProjectsForUser(this.value);
                } else {
                    projectSelection.style.display = 'none';
                }
            });

            // Function to fetch projects associated with the selected user
            function fetchProjectsForUser(userId) {
                fetch('<?= base_url('/projects/getProjectsForUser/') ?>' + userId)
                    .then(response => response.json())
                    .then(data => {
                        const selectProjects = document.getElementById('unassign_projects');
                        selectProjects.innerHTML = ''; // Clear previous options

                        // Check if data.projects is defined
                        if (data.projects) {
                            // Add options for each project
                            data.projects.forEach(project => {
                                const option = document.createElement('option');
                                option.value = project.projectID;
                                option.textContent = project.projectName;
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
    </div>
    <script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
