<?php $pageTitle = 'Dashboard' ?>
<!-- Header content -->
<header>
    <?php require_once 'header.php' ?>
</header>
<link rel="stylesheet" href="<?php echo base_url('/assets/css/dashboard.css') ?>">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div id="totalProjects" class="box">
        <a href="<?php echo base_url('/projects') ?>">
            <p id="totalProjectsText">Total Projects... <?= $totalProjects ?></p>
        </a>
    </div>

    <div id="ongoingProjects" class="box">
        <a href="<?php echo base_url('/projects/filter?status=1') ?>">
            <p id="ongoingProjectsText">Ongoing Projects... <?= count($ongoingProjects) ?></p>
        </a>
    </div>
    <div id="completedProjects" class="box">
        <a href="<?php echo base_url('/projects/filter?status=2') ?>">
            <p id="completedProjectsText">Completed Projects... <?= count($completedProjects) ?></p>
        </a>
    </div>
    <div id="myProjects" class="box">
        <a href="<?php echo base_url('/my_work') ?>">
            <p id="myProjectsText">My Projects... <?= count($assignedProjects) ?></p>
        </a>
    </div>
    <div id="myTasks" class="box">
        <a href="<?php echo base_url('/forums')?>">
        <p id="myTasksText">Forum Posts... <?= count($forumPosts) ?></p>
        </a>
    </div>
    <div id="upcomingDeadlines" class="box">
        <a href="<?php echo base_url('/calendar') ?>">
        <p id="upcomingDeadlinesText">Upcoming Deadlines... <?= $upcomingEventsCount ?></p>
        </a>
    </div>
</div>
</body>
</html>