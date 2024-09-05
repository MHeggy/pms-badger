<?php $pageTitle = "Reset Password" ?>
<!-- Header -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

<div class="container">
    <h2>Forgot Password</h2>

    <?php if (session('error')) : ?>
        <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif ?>

    <?php if (session('message')) : ?>
        <div class="alert alert-success"><?= session('message') ?></div>
    <?php endif ?>

    <form action="<?= url_to('processForgotPassword') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="email">Enter your email address:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Send Reset Link</button>
    </form>
</div>

</body>
</html>