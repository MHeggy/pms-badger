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
    <style>
        #profile-dropdown {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .notification-badge {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 0 6px;
            font-size: 12px;
            vertical-align: top;
            position: absolute;
            top: -10px;
            right: -10px;
        }
        /* Additional styling for the taskbar */
        #taskbarContainer {
            background-color: #333; /* Background color of the taskbar */
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2); /* Add a shadow at the bottom */
            position: fixed; /* Fixed positioning */
            top: 48px; /* Align below the header */
            left: 0; /* Align to the left side of the viewport */
            width: 100%; /* Take up full width */
            height: 100px; /* Height for taskbar */
            z-index: 999; /* Ensure taskbar is below header */
            padding: 10px 0; /* Add padding to the taskbar */
        }
        #taskbarItems {
            list-style-type: none; /* Remove bullet points */
            padding: 0; /* Remove default padding */
            margin: 0; /* Remove default margin */
        }
        #taskbarItems li {
            display: inline-block; /* Display items inline */
            margin-right: 15px;
            position: relative; /* Ensure relative positioning for badge */
        }
        #taskbarItems a {
            text-decoration: none;
            color: white; /* Text color for links */
            display: inline-block;
            padding: 10px; /* Add padding for better click area */
        }
        #taskbarItems li:hover {
            background-color: #444; /* Darken background on hover */
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
                <li><a class="dropdown-item" href="<?php echo base_url('/myprofile/' . $user->id) ?>">My Profile</a></li>
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
            <li><a href="<?php echo base_url('/dashboard') ?>">Dashboard</a></li>
            <?php if ($user->inGroup('accountant') || $user->inGroup('superadmin')): ?>
                <li><a href="<?php echo base_url('/accountantpayroll') ?>">Payroll</a></li>
            <?php endif; ?>
            <li><a href="<?php echo base_url('/timesheets') ?>">Timesheets</a></li>
            <?php if ($user->inGroup('superadmin')): ?>
                <li><a href="<?php echo base_url('/assignUsers') ?>">Assign Users</a></li>
                <li><a href="<?php echo base_url('/unassignUsers') ?>">Unassign Users</a></li>
                <li><a href="<?php echo base_url('/addProject') ?>">Add Projects</a></li>
            <?php endif; ?>
            <li><a href="<?php echo base_url('/projects') ?>">Projects</a></li>
            <li><a href="<?php echo base_url('/my_work') ?>">My Work</a></li>
            <li>
                <a href="<?php echo base_url('/calendar') ?>">Calendar</a>
                <?php if (isset($upcomingEventsCount) && $upcomingEventsCount > 0): ?>
                    <span class="notification-badge"><?= $upcomingEventsCount ?></span>
                <?php endif; ?>
            </li>
            <li><a href="<?php echo base_url('/forums') ?>">Forums</a></li>
        <?php else : ?>
            <!-- Show these if user is not logged in. -->
            <li><a href="<?php echo base_url('/login') ?>">Login</a></li>
            <li><a href="<?php echo base_url('/register') ?>">Register</a></li>
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