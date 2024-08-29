<?= $pageTitle = 'Settings' ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">

    <style>
        .navbar {
            position: fixed;
            top: 8%;
            width: 95%;
            left: 7.5%;
        }

        body {
            margin-top: 56px;
            padding-top: 15px;
        }

        .settings-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .settings-header {
            margin-bottom: 20px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>

<!-- header content -->
<header>
    <?php include 'header.php' ?>
</header>

<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <strong><a class="nav-link" href="<?php echo base_url('/settings') ?>">Profile Settings</a></strong>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('/user/change_password') ?>">Change Email/Password</a>
                </li>
                <!-- Add more options here -->
                <li class="nav-item">
                    <a class="nav-link" href="#">Layout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br><br><br><br><br>
<div class="container settings-container mt-5">
    <h2 class="settings-header">Profile Settings</h2>
    <form action="<?php echo base_url('/user/update_profile') ?>" method="post">
        <div class="mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName" name="first_name" value="<?= auth()->user()->first_name ?>" required>
        </div>
        <div class="mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="last_name" value="<?= auth()->user()->last_name ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= auth()->user()->email ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= auth()->user()->username ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>