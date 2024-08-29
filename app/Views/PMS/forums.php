<?= $pageTitle = "Forums"; ?>
<link rel="stylesheet" href="<?php echo base_url('/assets/css/messages.css') ?>">
    <!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Header content -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>
<main>
    <!-- Container for create post button and sort dropdown -->
    <div class="container mt-4 d-flex justify-content-between align-items-center">
        <!-- Create Post Button -->
        <button id="createPostBtn" class="btn btn-primary">Create Post</button>

        <!-- Sorting Dropdown -->
        <form id="sort-form" action="/forums" method="get" class="d-flex align-items-center">
            <label for="sort-category" class="me-2 mb-0">Sort by:</label>
            <select name="category_id" id="sort-category" class="form-select" onchange="document.getElementById('sort-form').submit();">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id']; ?>" <?= isset($_GET['category_id']) && $_GET['category_id'] == $category['id'] ? 'selected' : '' ?>><?= $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Post Form (Initially Hidden) -->
    <form id="post-form" action="/forums/createPost" method="post" class="container mt-4">
        <!-- Use Bootstrap form group to wrap the title input -->
        <div class="form-group mb-3">
            <input type="text" name="title" id="title" class="form-control" placeholder="Title" required>
        </div>
        <div class="form-group mb-3">
            <textarea name="content" id="content-input" class="form-control" placeholder="Write your post here..."></textarea>
        </div>
        <div class="form-group mb-3">
            <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Post</button>
    </form>

    <!-- Post container -->
    <div id="posts-container" class="container mt-4">
        <?php foreach ($posts as $post): ?>
            <a href="/forums/view/<?= $post['id']; ?>" class="text-decoration-none text-dark">
                <div class="post-card border rounded mb-3 p-3">
                    <!-- User Information -->
                    <div class="user-info mb-2">
                        <span class="author fw-bold"><?= $post['username']; ?></span>
                        <span class="timestamp text-muted"> | <?= date('F j, Y, g:i a', strtotime($post['created_at'])); ?></span>
                    </div>
                    <!-- Post Content -->
                    <div class="post-content">
                        <h5 class="mb-1"><?= $post['title']; ?></h5>
                        <p class="mb-0"><?= $post['content']; ?></p>
                        <p class="text-muted mt-2">Category: <?= $post['category_name']; ?></p>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?php echo base_url('/assets/js/messages.js') ?>"></script>
    <script src="<?php echo base_url('/assets/js/main.js')?>"></script>
    <!-- Include TinyMCE library -->
    <script src="https://cdn.tiny.cloud/1/eectismo1492cjcg16e1j17geuwjywoji6ldcnpay2cqxlay/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Initialize TinyMCE -->
    <script>
        tinymce.init({
            selector: 'textarea#content-input',  // Target the textarea with id="content-input"
            plugins: 'autolink lists link image',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image'
        });

        // Hide post form initially
        $('#post-form').hide();

        // Show/hide post form on button click
        $('#createPostBtn').click(function() {
            $('#post-form').toggle();
        });

    </script>
</main>
<!-- Script for main.js -->
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>