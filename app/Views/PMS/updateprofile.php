<?php $pageTitle = 'Update Profile' ?>

<!-- Header content -->
<header>
    <?php include 'header.php' ?>
</header>

<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
<style>
        /* Adjust the body margin to prevent content from being covered by the navbar */
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }
</style>

<?php $user = auth()->user(); ?>

<br><br><br>
<div class="container">
    <h1>Update Profile</h1>
    <form action="<?= base_url("/update_profile") ?>" method="post">
        <input type="hidden" id="id" name="id" value="<?= $user->id ?>">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $user->email ?>">
        </div>
        <div class="mb-3">
            <label for="firstName" class="form-label">First Name:</label>
            <input type="text" class="form-control" id="firstName" name="firstName" value="<?= $user->firstName ?>">
        </div>
        <div class="mb-3">
            <label for="lastName" class="form-label">Last Name:</label>
            <input type="text" class="form-control" id="lastName" name="lastName" value="<?= $user->lastName ?>">
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= $user->username ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>