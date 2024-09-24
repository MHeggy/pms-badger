<?php $pageTitle = 'Add Categories and Tasks' ?>
<!-- Header content -->
<header>
    <?php require_once 'header.php' ?>
</header>
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }

    .container {
        background-color: #ffffff; /* White background for forms */
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 30px;
    }
    h2 {
        border-bottom: 2px solid #007bff; /* Blue underline for headings */
        padding-bottom: 10px;
    }
    .btn {
        position: relative;
    }
    .btn i {
        margin-right: 5px; /* Space between icon and text */
    }
</style>

<div class="container mt-5">
    <h2>Add New Category</h2>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <form action="<?= base_url('/add_category') ?>" method="post">
        <div class="mb-3">
            <label for="categoryName" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="categoryName" name="categoryName" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i>Add Category
        </button>
    </form>

    <hr>

    <h2>Add New Task</h2>
    <form action="<?= base_url('/add_task') ?>" method="post">
        <div class="mb-3">
            <label for="taskName" class="form-label">Task Name</label>
            <input type="text" class="form-control" id="taskName" name="taskName" required>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i>Add Task
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>