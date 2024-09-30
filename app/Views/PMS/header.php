<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'PMSBadger' ?></title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
</head>
<body>
<?php $user = auth()->user(); ?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo base_url('/dashboard') ?>">
            <img src="<?php echo base_url('assets/img/BE Logo - New transparent.png') ?>" alt="Logo" class="header-logo" />
            PMSBadger
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (auth()->loggedIn()) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageTitle == 'Dashboard' ? 'active' : '' ?>" href="<?php echo base_url('/dashboard') ?>">Dashboard</a>
                    </li>
                    <?php if ($user->inGroup('accountant') || $user->inGroup('superadmin')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $pageTitle == 'Payroll [Accountant]' ? 'active' : '' ?>" href="<?php echo base_url('/accountant_payroll') ?>">Payroll</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageTitle == 'Timesheets' ? 'active' : '' ?>" href="<?php echo base_url('/timesheets') ?>">Timesheets</a>
                    </li>
                    <?php if ($user->inGroup('superadmin')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $pageTitle == '[Admin Page] Assign Users to Projects' ? 'active' : '' ?>" href="<?php echo base_url('/assign_users') ?>">Assign Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $pageTitle == '[Admin Page] Unassign Users from Projects' ? 'active' : '' ?>" href="<?php echo base_url('/unassign_users') ?>">Unassign Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $pageTitle == 'Add Categories and Tasks' ? 'active' : '' ?>" href="<?php echo base_url('/categories_tasks') ?>">Add Categories/Tasks</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageTitle == '[Admin] Add Projects' ? 'active' : '' ?>" href="<?php echo base_url('/add_project') ?>">Add Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageTitle == 'Projects' ? 'active' : '' ?>" href="<?php echo base_url('/projects') ?>">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageTitle == 'My Work' ? 'active' : '' ?>" href="<?php echo base_url('/my_work') ?>">My Work</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageTitle == 'Calendar' ? 'active' : '' ?>" href="<?php echo base_url('/calendar') ?>">Calendar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageTitle == 'Forums' ? 'active' : '' ?>" href="<?php echo base_url('/forums') ?>">Forums</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= $pageTitle == 'Support' ? 'active' : '' ?>" href="#" id="supportDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Support
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="supportDropdown">
                            <li><a class="dropdown-item" href="<?php echo base_url('/report_problem') ?>">Report a Problem</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('/contact') ?>">Contact</a></li>
                            <!-- Show 'View Support Tickets' only for superadmin group -->
                            <?php if ($user->inGroup('superadmin')): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('/view_support_tickets') ?>">View Support Tickets</a></li>
                            <?php else : ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('/my_tickets') ?>">View Support Tickets</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageTitle == 'Login' ? 'active' : '' ?>" href="<?php echo base_url('/login') ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pageTitle == 'Register' ? 'active' : '' ?>" href="<?php echo base_url('/register') ?>">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <?php if (auth()->loggedIn()) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?= $user->username ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?php echo base_url('/my_profile/' . $user->id) ?>">My Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('/settings') ?>">Settings</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('/change_password') ?>">Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" onclick="displayModal()">Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

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
