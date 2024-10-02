<?php $pageTitle = "Forums"; ?>

<!-- Header content -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

<link rel="stylesheet" href="<?php echo base_url('/assets/css/forums.css') ?>">
<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }

    /* Styles for the sidebar and forum content */
    .forum-sidebar {
        width: 20%;
        margin-right: 5%;
    }

    .forum-content {
        width: 75%;
    }

    .inner-main-header-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    #createPostBtn {
        white-space: nowrap;
        width: 100%;
    }

    .forum-content .card {
        margin-bottom: 15px;
    }
</style>

<main>
    <div class="container mt-4 d-flex">
        <!-- Sidebar: Sorting dropdown and Create Project Discussion button -->
        <div class="forum-sidebar">
            <div class="inner-main-header-container">
                <!-- Sorting Dropdown -->
                <select name="category_id" id="sort-category" class="form-select" onchange="document.getElementById('sort-form').submit();">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Create Post Button -->
                <button id="createPostBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDiscussionModal">
                    <i class="fas fa-plus"></i> Create Project Discussion
                </button>
            </div>
        </div>

        <!-- Forum Posts Section -->
        <div class="forum-content">
            <?php foreach ($posts as $post): ?>
                <div class="card">
                    <div class="card-body p-2 p-sm-3">
                        <div class="media forum-item">
                            <div class="media-body">
                                <h6><a href="<?= base_url('/forums/post/' . $post['id']) ?>" class="text-body"><?= $post['title'] ?></a></h6>
                                <p class="text-secondary"><?= $post['content'] ?></p>
                                <p class="text-muted">
                                    <a href="javascript:void(0)"><?= $post['username'] ?></a> 
                                    posted in 
                                    <a href="<?= base_url('/forums/category/' . $post['category_id']) ?>"><?= $post['category_name'] ?></a>
                                    <span class="text-secondary font-weight-bold"><?= date('F j, Y', strtotime($post['created_at'])) ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<!-- Create Project Discussion Modal -->
<div class="modal fade" id="createDiscussionModal" tabindex="-1" aria-labelledby="createDiscussionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('/forums/create') ?>" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="createDiscussionModalLabel">
                        <i class="fas fa-comments"></i> Create New Project Discussion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Title input -->
                    <div class="mb-3">
                        <label for="title" class="form-label"><i class="fas fa-heading"></i> Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter discussion title" required>
                    </div>
                    <!-- Content input -->
                    <div class="mb-3">
                        <label for="content" class="form-label"><i class="fas fa-align-left"></i> Content</label>
                        <textarea class="form-control" id="content" name="content" rows="5" placeholder="Write your content here..." required></textarea>
                    </div>
                    <!-- Category select -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label"><i class="fas fa-list-ul"></i> Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Create Discussion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap and jQuery JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>