<?= $pageTitle = '[Admin Page] Assign Users to Projects' ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/assignusers.css')?>">
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
            <form action="<?php echo base_url('/projects/assign') ?>" method="post" id="userSelectionForm">
                <div class="mb-3" id="assignSelection">
                    <label for="user" class="form-label">Select User:</label>
                    <select class="form-select" name="user" id="user">
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
                <select name="projects[]" id="projects" multiple>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?= $project['projectID']; ?>"><?= $project['projectName']; ?></option>
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
        if (this.value !== '') {
            projectSelection.style.display = 'block';
        } else {
            projectSelection.style.display = 'none';
        }
    });
</script>
    <script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>
