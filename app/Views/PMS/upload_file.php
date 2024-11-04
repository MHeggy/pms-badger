<?php $pageTitle = "Upload File(s)"; ?>

<header>
    <?php include 'header.php'; ?>
</header>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/payroll.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<div class="container mt-5">
    <h2 class="text-center">Upload File to MEGA Cloud</h2>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="mt-4 text-center">
        <h4>Select Category:</h4>
        <form action="<?= base_url('file/upload') ?>" method="post" enctype="multipart/form-data">
            <!-- Category Dropdown -->
            <select class="form-select mb-3" id="category-select" name="category">
                <option selected>Select a category</option>
                <option value="projects">Projects</option>
                <option value="tasks">Tasks</option>
                <!-- Add more options as needed -->
            </select>
            
            <!-- File Input -->
            <input type="file" name="file" class="form-control mb-3" required>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-cloud-upload-alt"></i> Upload to Selected Category on MEGA
            </button>
        </form>
    </div>
</div>
