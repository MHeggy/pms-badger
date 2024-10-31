<?php $pageTitle = "Upload File(s)"; ?>

<!-- Header content -->
<header>
    <?php include 'header.php'; ?>
</header>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/payroll.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
<!-- Bootstrap Icons CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<div class="container mt-5">
    <h2 class="text-center">Upload File to MEGA Cloud</h2>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="mt-4 text-center">
        <h4>Upload directly via MEGA:</h4>
        <a href="https://mega.nz/filerequest/65HLDsIkxY0" class="btn btn-primary" target="_blank">
            <i class="fas fa-cloud-upload-alt"></i> Click here to upload
        </a>
    </div>
</div>

</body>
</html>