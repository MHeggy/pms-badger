<?php $pageTitle = 'Dashboard' ?>
<!-- Header content -->
<header>
    <?php require_once 'header.php' ?>
</header>
<!-- Link to external CSS file -->
<link rel="stylesheet" href="<?php echo base_url('/assets/css/dashboard.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
    #content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }

    .box {
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
        text-align: center;
    }

    .box:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        background-color: #f8f9fa;
    }

    .card {
        text-align: center;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card i {
        font-size: 2rem;
        color: #007bff;
        margin-bottom: 10px;
    }
</style>
<!-- Content -->
<div id="content">
    <!-- Display success message or error message if they exist -->
    <?php if (session()->get('success_message') || !empty($errorMessage)): ?>
        <div class="container">
            <?php if (session()->get('success_message')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->get('success_message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $errorMessage ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Start of actual home page content. -->
    <div class="card">
        <i class="fas fa-project-diagram"></i>
        <h5 class="card-title">Total Projects</h5>
        <p class="card-text"><?= $totalProjects ?></p>
        <a href="<?php echo base_url('/projects') ?>" class="btn btn-primary">View</a>
    </div>

    <div class="card">
        <i class="fas fa-spinner"></i>
        <h5 class="card-title">Ongoing Projects</h5>
        <p class="card-text"><?= count($ongoingProjects) ?></p>
        <a href="<?php echo base_url('/projects/filter?status=1') ?>" class="btn btn-primary">View</a>
    </div>

    <div class="card">
        <i class="fas fa-check-circle"></i>
        <h5 class="card-title">Completed Projects</h5>
        <p class="card-text"><?= count($completedProjects) ?></p>
        <a href="<?php echo base_url('/projects/filter?status=2') ?>" class="btn btn-primary">View</a>
    </div>

    <div class="card">
        <i class="fas fa-folder-open"></i>
        <h5 class="card-title">My Projects</h5>
        <p class="card-text"><?= count($assignedProjects) ?></p>
        <a href="<?php echo base_url('/my_work') ?>" class="btn btn-primary">View</a>
    </div>

    <div class="card">
        <i class="fas fa-comments"></i>
        <h5 class="card-title">Forum Posts</h5>
        <p class="card-text"><?= count($forumPosts) ?></p>
        <a href="<?php echo base_url('/forums') ?>" class="btn btn-primary">View</a>
    </div>

    <div class="card">
        <i class="fas fa-calendar-alt"></i>
        <h5 class="card-title">Upcoming Deadlines</h5>
        <p class="card-text"><?= $upcomingEventsCount ?></p>
        <a href="<?php echo base_url('/calendar') ?>" class="btn btn-primary">View</a>
    </div>

    <!-- New Timesheets Box -->
    <div class="card">
        <i class="fas fa-clock"></i>
        <h5 class="card-title">My Timesheets</h5>
        <p class="card-text"><?= $totalTimesheets ?></p> <!-- Assuming you have this variable available -->
        <a href="<?php echo base_url('/timesheets') ?>" class="btn btn-primary">View</a>
    </div>

    <!-- New Profile Box -->
    <div class="card">
        <i class="fas fa-user"></i>
        <h5 class="card-title">My Profile</h5>
        <p class="card-text">Manage your profile settings</p>
        <a href="<?php echo base_url('/profile') ?>" class="btn btn-primary">View</a>
    </div>
</div>
</body>
</html>