<?php $pageTitle = '[Admin Page] Assign Users to Projects'; ?>

<!-- Header content -->
<header>
    <?php include 'header.php'; ?>
</header>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/assignusers.css') ?>">
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
        padding: 20px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        text-align: center;
        align-items: center;
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

    .btn-custom {
        display: flex;
        align-items: center;
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

    <div class="card">
        <h2 class="text-center mb-4"><i class="bi bi-person-plus icon"></i> Assign Users to Projects</h2>
        <form action="<?php echo base_url('/projects/assign') ?>" method="post" id="userSelectionForm">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="user" class="form-label">Select User:</label>
                    <select class="form-select" name="user" id="user">
                        <option value="">Select User</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user->id; ?>"><?= esc($user->firstName . ' ' . $user->lastName); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3" id="projectSelection" style="display: none;">
                    <label for="projects" class="form-label">Select Projects:</label>
                    <select class="form-select" name="projects[]" id="projects" multiple>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-custom mt-2">
                    <i class="bi bi-check-circle"></i> Assign User to Project(s)
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Show project selection when a user is selected
    document.getElementById('user').addEventListener('change', function() {
        var projectSelection = document.getElementById('projectSelection');
        var selectedUserId = this.value;

        if (selectedUserId !== '') {
            projectSelection.style.display = 'block';
            // Fetch and display unassigned projects associated with the selected user
            fetchUnassignedProjectsForUser(selectedUserId);
        } else {
            projectSelection.style.display = 'none';
        }
    });

    function fetchUnassignedProjectsForUser(userId) {
        fetch('<?= base_url('/projects/getUnassignedProjectsForUser/') ?>' + userId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const selectProjects = document.getElementById('projects');
                selectProjects.innerHTML = ''; // Clear previous options

                if (data.projects) {
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