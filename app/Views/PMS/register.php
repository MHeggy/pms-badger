<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle = "Register"; ?></title>
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/register.css') ?>">
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Header -->
<div id="header">
    <header>
        <?php include 'header.php' ?>
    </header>
</div>

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
                    <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
                </div>

                <!-- Username -->
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="floatingUsernameInput" name="username" inputmode="text" autocomplete="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>" required>
                    <label for="floatingUsernameInput"><?= lang('Auth.username') ?></label>
                </div>

                <!-- First and Last Names -->
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" id="floatingFirstnameInput" name="first_name" inputmode="text" autocomplete="first_name" placeholder="First Name" required>
                    <label for="floatingFirstnameInput">First Name</label>
                </div>

                <!-- First and Last Names -->
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="floatingLastnameInput" name="last_name" inputmode="text" autocomplete="last_name" placeholder="Last Name" required>
                    <label for="floatingFirstnameInput">Last Name</label>
                </div>

                <!-- Password -->
                <div class="form-floating mb-2">
                    <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.password') ?>" required>
                    <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
                </div>

                <!-- Password (Again) -->
                <div class="form-floating mb-5">
                    <input type="password" class="form-control" id="floatingPasswordConfirmInput" name="password_confirm" inputmode="text" autocomplete="new-password" placeholder="<?= lang('Auth.passwordConfirm') ?>" required>
                    <label for="floatingPasswordConfirmInput"><?= lang('Auth.passwordConfirm') ?></label>
                </div>

                <div class="d-grid col-12 col-md-8 mx-auto m-3">
                    <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.register') ?></button>
                </div>

                <p class="text-center"><?= lang('Auth.haveAccount') ?> <a href="<?= url_to('login') ?>"><?= lang('Auth.login') ?></a></p>

            </form>
        </div>
    </div>
</div>
</body>
</html>
