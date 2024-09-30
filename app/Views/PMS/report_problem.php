<?php esc($pageTitle = 'Report a Problem') ?>

<header>
    <?php include 'header.php'; ?>
</header>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

<div class="container mt-5">
    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('message'); ?>
        </div>
    <?php endif; ?>

    <h1 class="mb-4">Report a Problem</h1>
    <form action="<?php echo base_url('/report_problem') ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="problemTitle" class="form-label">Problem Title</label>
            <input type="text" class="form-control" id="problemTitle" name="problemTitle" placeholder="Brief title of the issue" required>
        </div>

        <div class="mb-3">
            <label for="problemDescription" class="form-label">Problem Description</label>
            <textarea class="form-control" id="problemDescription" name="problemDescription" rows="5" placeholder="Describe the problem in detail" required></textarea>
        </div>

        <div class="mb-3">
            <label for="priority" class="form-label">Priority</label>
            <select class="form-select" id="priority" name="priority" required>
                <option value="" disabled selected>Select priority</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="attachment" class="form-label">Attach a File (Optional)</label>
            <input class="form-control" type="file" id="attachment" name="attachment">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

</body>
</html>