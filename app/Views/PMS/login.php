<?php $pageTitle = "Login"; ?>

<!-- Include Bootstrap CSS and FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('/assets/css/login.css') ?>">

<!-- Custom Font from Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<!-- Header -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

<!-- Body content -->
<div class="container d-flex justify-content-center p-5">
    <div class="card col-12 col-md-5 shadow-sm login-container">
        <div class="card-body">
            <h5 class="card-title mb-5"><?= lang('Auth.login') ?></h5>

            <?php if (session('error') !== null) : ?>
                <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
            <?php elseif (session('errors') !== null) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (is_array(session('errors'))) : ?>
                        <?php foreach (session('errors') as $error) : ?>
                            <?= $error ?><br>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?= session('errors') ?>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <form action="<?= url_to('login') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Email Input -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
                    <label for="floatingEmailInput"><i class="fas fa-envelope"></i> <?= lang('Auth.email') ?></label>
                </div>

                <!-- Password Input with Toggle Visibility Icon -->
                <div class="form-floating mb-3 position-relative">
                    <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required>
                    <label for="floatingPasswordInput"><i class="fas fa-lock"></i> <?= lang('Auth.password') ?></label>
                    <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y pe-2" onclick="togglePassword('floatingPasswordInput')" style="cursor: pointer;"></i>
                </div>

                <!-- Remember Me Checkbox -->
                <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')): ?> checked<?php endif ?>>
                            <?= lang('Auth.rememberMe') ?>
                        </label>
                    </div>
                <?php endif; ?>

                <!-- Submit Button with Icon -->
                <div class="d-grid col-12 col-md-8 mx-auto m-3">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i> <?= lang('Auth.login') ?>
                    </button>
                </div>

                <!-- Additional Links -->
                <p class="text-center">
                    <?= lang('Auth.forgotPassword') ?> 
                    <a href="<?= url_to('forgot-password') ?>">Reset it here</a>
                </p>

                <?php if (setting('Auth.allowRegistration')) : ?>
                    <p class="text-center"><?= lang('Auth.needAccount') ?> <a href="<?= url_to('register') ?>"><?= lang('Auth.register') ?></a></p>
                <?php endif ?>

            </form>
        </div>
    </div>
</div>

<!-- Include FontAwesome and Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<!-- Toggle Password Visibility Script -->
<script>
function togglePassword(id) {
    var passwordField = document.getElementById(id);
    var icon = passwordField.nextElementSibling;
    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>