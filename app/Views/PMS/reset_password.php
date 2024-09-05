<?php $pageTitle = "Reset Password" ?>
<!-- Header -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>
<!-- Style sheet -->
<link rel="stylesheet" href="<?= base_url('/assets/css/login.css') ?>">
<link rel="stylesheet" href="<?= base_url('/assets/css/forgot_password.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container">
    <h2>Reset Your Password</h2>

    <?php if (session('error')) : ?>
        <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif ?>

    <form action="<?= url_to('reset-password') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= $token ?>">

        <div class="form-group">
            <label for="password">New Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirm">Confirm Password:</label>
            <input type="password" name="password_confirm" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Reset Password</button>
    </form>
</div>

</body>
</html>