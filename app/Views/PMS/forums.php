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
                <button id="createPostBtn" class="btn btn-primary">
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