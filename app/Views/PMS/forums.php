<?php $pageTitle = "Forums"; ?>

<!-- Header content -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css">

<!-- Link to custom forums.css -->
<link rel="stylesheet" href="<?php echo base_url('/assets/css/forums.css') ?>">

<main>
    <div class="container mt-4">
        <div class="inner-wrapper">
            <!-- Sidebar -->
            <div class="inner-sidebar">
                <div class="inner-sidebar-header justify-content-center">
                    <button class="btn btn-primary has-icon btn-block">
                        <i class="fas fa-plus mr-2"></i> NEW DISCUSSION
                    </button>
                </div>
                <div class="inner-sidebar-body p-3">
                    <nav class="nav nav-pills flex-column">
                        <a href="#" class="nav-link nav-link-faded has-icon active">All Threads</a>
                        <a href="#" class="nav-link nav-link-faded has-icon">Popular this week</a>
                        <a href="#" class="nav-link nav-link-faded has-icon">Solved</a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="inner-main">
                <!-- Forum Controls -->
                <div class="inner-main-header d-flex justify-content-between align-items-center mb-4">
                    <select class="custom-select custom-select-sm w-auto">
                        <option selected>Latest</option>
                        <option>Popular</option>
                        <option>Solved</option>
                    </select>
                    <span class="input-icon w-auto">
                        <input type="text" class="form-control form-control-sm bg-gray-200 border-gray-200" placeholder="Search forum">
                    </span>
                </div>

                <!-- Forum Post List -->
                <div class="inner-main-body p-2">
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div class="media forum-item">
                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="mr-3 rounded-circle" width="50">
                                <div class="media-body">
                                    <h6><a href="#" class="text-body">Post Title</a></h6>
                                    <p class="text-secondary">Post content goes here...</p>
                                    <p class="text-muted">Author - <span class="text-secondary font-weight-bold">5 minutes ago</span></p>
                                </div>
                                <div class="text-muted small text-center align-self-center">
                                    <span class="d-none d-sm-inline-block"><i class="far fa-eye"></i> 19</span>
                                    <span><i class="far fa-comment ml-2"></i> 3</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>