<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'PMSBadger' ?></title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<?php $user = auth()->user(); ?>

<!-- Main header -->
<div id="headerContainer" class="container-fluid">
    <div class="row align-items-center">
        <div class="col-lg-6 d-flex justify-content-start">
            <ul id="taskbarItems" class="d-flex">
                <li><a href="<?php echo base_url('/dashboard') ?>" class="nav-link"><i class="bi bi-house-door"></i></a></li>
                <li><a href="<?php echo base_url('/timesheets') ?>" class="nav-link"><i class="bi bi-clock"></i></a></li>
                <li><a href="<?php echo base_url('/projects') ?>" class="nav-link"><i class="bi bi-folder"></i></a></li>
                <li><a href="<?php echo base_url('/calendar') ?>" class="nav-link"><i class="bi bi-calendar"></i></a></li>
                <li><a href="<?php echo base_url('/my_work') ?>" class="nav-link"><i class="bi bi-list-task"></i></a></li>
                <?php if ($user->inGroup('superadmin')): ?>
                    <li><a href="<?php echo base_url('/assign_users') ?>" class="nav-link"><i class="bi bi-person-plus"></i></a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="col-lg-6 d-flex justify-content-end">
            <img src="<?php echo base_url('assets/img/BE Logo - New blue background.png') ?>" alt="Logo" class="header-logo" />
            <li class="taskbar-item dropdown" id="profile-dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> <?= $user->username ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?php echo base_url('/my_profile/' . $user->id) ?>">My Profile</a></li>
                    <li><a class="dropdown-item" href="<?php echo base_url('/settings') ?>">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" onclick="displayModal()">Logout</a></li>
                </ul>
            </li>
        </div>
    </div>
</div>

<!-- Logout modal -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h1>Logout</h1>
        <p>Are you sure you want to logout?</p>
        <div class="buttons">
            <button class="button" onclick="logout()" id="yesBtn">Yes</button>
            <button class="button" onclick="closeModal()" id="noBtn">No</button>
        </div>
    </div>
</div>
    <script>
        document.getElementById('taskbarToggle').addEventListener('click', function() {
            var taskbarItems = document.getElementById('taskbarItems');
            taskbarItems.style.display = taskbarItems.style.display === 'flex' ? 'none' : 'flex';
            this.classList.toggle('active');
        });
    </script>
    <script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>