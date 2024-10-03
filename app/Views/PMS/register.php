<?php $pageTitle = "Register"; ?>

<!-- Header -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

<!-- Link to external CSS file -->
<link rel="stylesheet" href="<?php echo base_url('/assets/css/register.css') ?>">
<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }
</style>
<div class="container d-flex justify-content-center p-5">
    <div class="card col-12 col-md-5 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-5"><?= lang('Auth.register') ?></h5>

            <?php if (session('error') !== null) : ?>
                <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
            <?php elseif (session('errors') !== null) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (is_array(session('errors'))) : ?>
                        <?php foreach (session('errors') as $error) : ?>
                            <?= $error ?>
                            <br>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?= session('errors') ?>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <form action="<?= url_to('register') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Email -->
            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
                <label for="floatingEmailInput"><i class="fas fa-envelope"></i> <?= lang('Auth.email') ?></label>
            </div>

            <!-- Username -->
            <div class="form-floating mb-4">
                <input type="text" class="form-control" id="floatingUsernameInput" name="username" inputmode="text" autocomplete="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>" required>
                <label for="floatingUsernameInput"><i class="fas fa-user"></i> <?= lang('Auth.username') ?></label>
            </div>

            <!-- First Name -->
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="floatingFirstnameInput" name="firstName" inputmode="text" autocomplete="first_name" placeholder="First Name" required>
                <label for="floatingFirstnameInput"><i class="fas fa-id-badge"></i> First Name</label>
            </div>

            <!-- Last Name -->
            <div class="form-floating mb-4">
                <input type="text" class="form-control" id="floatingLastnameInput" name="lastName" inputmode="text" autocomplete="last_name" placeholder="Last Name" required>
                <label for="floatingLastnameInput"><i class="fas fa-id-badge"></i> Last Name</label>
            </div>

            <!-- Password -->
            <div class="form-floating mb-2 position-relative">
                <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.password') ?>" required>
                <label for="floatingPasswordInput"><i class="fas fa-lock"></i> <?= lang('Auth.password') ?></label>
                <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y pe-2" onclick="togglePassword('floatingPasswordInput')" style="cursor: pointer;"></i>
            </div>

            <!-- Confirm Password -->
            <div class="form-floating mb-5 position-relative">
                <input type="password" class="form-control" id="floatingPasswordConfirmInput" name="password_confirm" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.passwordConfirm') ?>" required>
                <label for="floatingPasswordConfirmInput"><i class="fas fa-lock"></i> <?= lang('Auth.passwordConfirm') ?></label>
                <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y pe-2" onclick="togglePassword('floatingPasswordConfirmInput')" style="cursor: pointer;"></i>
            </div>

            <div class="g-recaptcha" data-sitekey="6LfE21YqAAAAAEoXv_de7Qq58dcgt2OY_AAvCwOE"></div>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>

            <!-- Submit Button -->
            <div class="d-grid col-12 col-md-8 mx-auto m-3">
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-user-plus"></i> <?= lang('Auth.register') ?></button>
            </div>

            <p class="text-center"><?= lang('Auth.haveAccount') ?> <a href="<?= url_to('login') ?>"><i class="fas fa-sign-in-alt"></i> <?= lang('Auth.login') ?></a></p>

            </form>
        </div>
    </div>
</div>
<!-- Script for showing the current password typed into the bar --> 
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
