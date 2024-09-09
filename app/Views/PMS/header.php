<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'PMSBadger' ?></title>
    <!-- Script for bootstrap and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        #profile-dropdown {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        /* Taskbar items styling */
        #taskbarItems {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }

        #taskbarItems li {
            position: relative;
            margin-right: 20px;
        }

        #taskbarItems a {
            text-decoration: none;
            color: inherit;
        }

        .notification-badge {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 0 6px;
            font-size: 12px;
            position: absolute;
            top: -10px;
            right: -10px;
        }

        /* Additional styles for badge alignment */
        .taskbar-item {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .taskbar-item .notification-badge {
            position: absolute;
            top: 0;
            right: -10px;
        }

        a {
            text-decoration: none; /* Removes underline from all links */
            color: inherit; /* Ensures links inherit the text color */
        }

        a:hover {
            text-decoration: underline; /* Optional: Add underline on hover */
        }
    </style>
</head>
<body>
<?php $user = auth()->user(); ?>
<!-- Main title of header -->
<div id="titleContainer">
    <h2 id="headerTitle"><?= $pageTitle ?></h2>

    <!-- Profile dropdown -->
    <?php if (auth()->loggedIn()) : ?>
        <div class="dropdown ms-auto" id="profile-dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <?= $user->username ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="<?php echo base_url('/my_profile/' . $user->id) ?>">My Profile</a></li>
                <li><a class="dropdown-item" href="<?php echo base_url('/settings') ?>">Settings</a></li>
                <li><a class="dropdown-item" href="<?php echo base_url('/personalmessages') ?>">My Messages</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" onclick="displayModal()">Logout</a></li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<!-- Taskbar part of the header. -->
<div id="taskbarContainer">
    <ul id="taskbarItems">
        <?php if (auth()->loggedIn()) : ?>
            <!-- Show these if user is logged in. -->
            <a href="<?php echo base_url('/dashboard') ?>"><li>Dashboard</li></a>
            <?php if ($user->inGroup('accountant') || $user->inGroup('superadmin')): ?>
                <a href="<?php echo base_url('/accountant_payroll') ?>"><li>Payroll</li></a>
            <?php endif; ?>
            <a href="<?php echo base_url('/timesheets') ?>"><li>Timesheets</li></a>
            <?php if ($user->inGroup('superadmin')): ?>
                <a href="<?php echo base_url('/assign_users') ?>"><li>Assign Users</li></a>
                <a href="<?php echo base_url('/unassign_users') ?>"><li>Unassign Users</li></a>
                <a href="<?php echo base_url('/add_project') ?>"><li>Add Projects</li></a>
            <?php endif; ?>
            <a href="<?php echo base_url('/projects') ?>"><li>Projects</li></a>
            <a href="<?php echo base_url('/my_work') ?>"><li>My Work</li></a>
            <li class="taskbar-item">
                <a href="<?php echo base_url('/calendar') ?>">Calendar</a>
                <?php if (isset($upcomingEventsCount) && $upcomingEventsCount > 0): ?>
                    <span class="notification-badge"><?= $upcomingEventsCount ?></span>
                <?php endif; ?>
            </li>
        <?php else : ?>
            <!-- Show these if user is not logged in. -->
            <a href="<?php echo base_url('/login') ?>"><li>Login</li></a>
            <a href="<?php echo base_url('/register') ?>"><li>Register</li></a>
        <?php endif; ?>
    </ul>
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

<script src="<?php echo base_url('/assets/js/main.js')?>"></script>