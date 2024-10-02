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

    /* Additional Styles */
    .inner-main-header-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .inner-main-header {
        display: flex;
        align-items: center;
    }

    .inner-main-header select {
        margin-left: 10px;
    }

    #createPostBtn {
        margin-left: auto;
        white-space: nowrap;
    }

    .forum-content {
        margin-left: 15%;
    }
</style>

<main>
    <div class="container mt-4">
        <div class="d-flex">
            <!-- Inner main header section (side content) -->
            <div class="inner-main-header-container">
                <div class="inner-main-header">
                    <!-- Sorting Dropdown -->
                    <select name="category_id" id="sort-category" class="form-select" onchange="document.getElementById('sort-form').submit();">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    
                    <!-- Search Bar -->
                    <span class="input-icon input-icon-sm ml-3">
                        <input type="text" class="form-control form-control-sm bg-gray-200 border-gray-200 shadow-none" placeholder="Search forum" />
                    </span>
                </div>

                <!-- Create Post Button -->
                <button id="createPostBtn" class="btn btn-primary ml-3">
                    <i class="fas fa-plus"></i> Create Project Discussion
                </button>
            </div>
        </div>

        <!-- Forum Posts Section -->
        <div class="forum-content">
            <?php foreach ($posts as $post): ?>
                <div class="card mb-2">
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
