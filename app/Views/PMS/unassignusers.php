<?php $pageTitle = '[Admin Page] Unassign Users from Projects' ?>

<!-- Header content -->
<header>
    <?php include 'header.php' ?>
</header>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/unassignusers.css') ?>">

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }

    .form-label {
        font-weight: bold;
    }

    .container {
        margin-top: 50px;
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .alert {
        margin-bottom: 20px;
    }

    .icon {
        margin-right: 5px;
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

    <div class="card p-4">
        <h2 class="text-center mb-4"><i class="bi bi-person-x icon"></i> Unassign Users from Projects</h2>
        <form action="<?php echo base_url('/projects/unassign') ?>" method="post" id="userSelectionForm">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="unassign_user" class="form-label">Select User:</label>
                    <select class="form-select" name="unassign_user" id="unassign_user" required>
                        <option value="">Select User</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user->id; ?>"><?= $user->firstName . ' ' . $user->lastName; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3" id="projectSelection" style="display: none;">
                    <label for="unassign_projects" class="form-label">Select Projects:</label>
                    <select class="form-select" name="unassign_projects[]" id="unassign_projects" multiple required>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-x-circle icon"></i> Unassign User from Project(s)
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Show project selection when user is selected.
    document.getElementById('unassign_user').addEventListener('change', function () {
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
<script src="<?php echo base_url('/assets/js/main.js') ?>"></script>
</body>
</html>