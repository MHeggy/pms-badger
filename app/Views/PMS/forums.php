<?php $pageTitle = "Forums"; ?>
<link rel="stylesheet" href="<?php echo base_url('/assets/css/forums.css') ?>">
<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />

<!-- Header content -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }
</style>

<main>
    <div class="container mt-4">
        <div class="inner-main">
            <!-- Inner main header -->
            <div class="inner-main-header d-flex justify-content-between align-items-center mb-4">
                <a class="nav-link nav-icon rounded-circle nav-link-faded mr-3 d-md-none" href="#" data-toggle="inner-sidebar">
                    <i class="material-icons">arrow_forward_ios</i>
                </a>
                <select class="custom-select custom-select-sm w-auto mr-1" id="sort-dropdown">
                    <option selected="">Latest</option>
                    <option value="popular">Popular</option>
                    <option value="solved">Solved</option>
                    <option value="unsolved">Unsolved</option>
                    <option value="no-replies">No Replies Yet</option>
                </select>
                <span class="input-icon input-icon-sm ml-auto w-auto">
                    <input type="text" class="form-control form-control-sm bg-gray-200 border-gray-200 shadow-none mb-4 mt-4" placeholder="Search forum" />
                </span>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Create Post Button -->
            <button id="createPostBtn" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Project Discussion
            </button>

            <!-- Sorting Dropdown -->
            <form id="sort-form" action="/forums" method="get" class="d-flex align-items-center">
                <label for="sort-category" class="me-2 mb-0">Sort by:</label>
                <select name="category_id" id="sort-category" class="form-select" onchange="document.getElementById('sort-form').submit();">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <!-- Forum Posts Section -->
        <div class="forum-content">
            <?php foreach ($posts as $post): ?>
                <div class="card mb-2">
                    <div class="card-body p-2 p-sm-3">
                        <div class="media forum-item">
                            <div class="media-body">
                                <h6><a href="<?= base_url('/forums/post/' . $post['id']) ?>" class="text-body"><?= $post['title'] ?></a></h6>
                                <p class="text-secondary"><?= $post['excerpt'] ?></p>
                                <p class="text-muted">
                                    <a href="javascript:void(0)"><?= $post['username'] ?></a> 
                                    posted in 
                                    <a href="<?= base_url('/forums/category/' . $post['category_id']) ?>"><?= $post['category_name'] ?></a>
                                    <span class="text-secondary font-weight-bold"><?= time_elapsed_string($post['created_at']) ?></span>
                                </p>
                            </div>
                            <div class="text-muted small text-center align-self-center">
                                <span class="d-none d-sm-inline-block"><i class="far fa-eye"></i> <?= $post['views'] ?></span>
                                <span><i class="far fa-comment ml-2"></i> <?= $post['replies_count'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</main>