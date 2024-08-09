<?php $pageTitle = 'Change Password' ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">
    <style>
        /* Add custom styles for the navigation bar */
        .navbar {
            position: fixed;
            top: 8%;
            width: 95%;
            left: 7.5%;
        }


        body {
            margin-top: 56px; /* Adjust based on the navbar height */
            padding-top: 15px; /* Add padding to the top content */
        }

        /* Optional: Add styles to make the navbar stand out */
        .navbar-nav .nav-link {
            color: #333; /* Set link color */
        }

        form {
            max-width: 400px;
            width: 100%;
            margin: auto; /* Center the form horizontally */
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            margin-top: 170px; /* Add some space between navbar and form */
        }

        label {
            font-weight: bold;
        }

        input[type="password"] {
            margin-bottom: 20px;
        }

        button[type="submit"] {
            width: 100%;
        }

    </style>
    <!-- Header content -->
    <header>
        <?php include 'header.php' ?>
    </header>

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('/settings') ?>">Profile Settings</a>
                    </li>
                    <li class="nav-item">
                        <strong><a class="nav-link" href="<?php echo base_url('/user/change_password') ?>">Change Email/Password</a></strong>
                    </li>
                    <!-- Add more options here -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">Layout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Change password form -->
    <form action="<?php echo base_url('/change_password') ?>" method="post">
        <h3 class="mb-4">Change Password</h3>
        <div class="mb-3">
            <label for="password" class="form-label">New Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <label for="confirm" class="form-label">Confirm Password:</label>
            <input type="password" class="form-control" id="confirm" name="confirm">
        </div>
        <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
</body>
</html>