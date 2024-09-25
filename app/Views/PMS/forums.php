<?php $pageTitle = "Forums"; ?>
<link rel="stylesheet" href="<?php echo base_url('/assets/css/messages.css') ?>">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Create Post Button -->
            <button id="createPostBtn" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Post
            </button>

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

        <!-- Forum List -->
        <div id="posts-container" class="mt-4">
            <?php foreach ($posts as $post): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="media forum-item">
                            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="mr-3 rounded-circle" width="50" alt="User" />
                            <div class="media-body">
                                <h5><a href="/forums/view/<?= $post['id']; ?>" class="text-body"><?= $post['title']; ?></a></h5>
                                <p class="text-secondary"><?= $post['content']; ?></p>
                                <p class="text-muted"><strong><?= $post['username']; ?></strong> | <?= date('F j, Y, g:i a', strtotime($post['created_at'])); ?></p>
                                <p class="text-muted">Category: <?= $post['category_name']; ?></p>
                            </div>
                            <div class="text-muted small text-center align-self-center">
                                <span class="d-none d-sm-inline-block"><i class="far fa-eye"></i> 19</span>
                                <span><i class="far fa-comment ml-2"></i> 3</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?php echo base_url('/assets/js/messages.js') ?>"></script>
    <script src="<?php echo base_url('/assets/js/main.js') ?>"></script>
    <script src="https://cdn.tiny.cloud/1/eectismo1492cjcg16e1j17geuwjywoji6ldcnpay2cqxlay/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Initialize TinyMCE -->
    <script>
        tinymce.init({
            selector: 'textarea#content-input',
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
</body>
</html>