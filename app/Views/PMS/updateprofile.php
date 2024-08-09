<?= $pageTitle = 'Update Profile' ?>
<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Add custom styles for the navigation bar */
        .navbar {
            position: fixed; /* Fix the navbar at the top of the viewport */
            top: 8%; /* Position at the top */
            width: 95%; /* Full width */
            left: 7.5%;
        }

        /* Adjust the body margin to prevent content from being covered by the navbar */
        body {
            margin-top: 56px; /* Adjust based on the navbar height */
            padding-top: 15px; /* Add padding to the top content */
        }

        /* Optional: Add styles to make the navbar stand out */
        .navbar-nav .nav-link {
            color: #333; /* Set link color */
        }
    </style>

<?php $user = auth()->user(); ?>

<!-- Header content -->
<header>
    <?php include 'header.php' ?>
</header>

<!-- Settings taskbar -->
<?php include 'settingstaskbar.php' ?><br><br><br><br><br><br>
<div class="container">
    <h1>Update Profile</h1>
    <form action="<?= base_url("/update_profile") ?>" method="post">
        <input type="hidden" id="id" name="id" value="<?= $user->id ?>">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $user->email ?>">
        </div>
        <div class="mb-3">
            <label for="firstName" class="form-label">First Name:</label>
            <input type="text" class="form-control" id="firstName" name="first_name" value="<?= $user->first_name ?>">
        </div>
        <div class="mb-3">
            <label for="lastName" class="form-label">Last Name:</label>
            <input type="text" class="form-control" id="lastName" name="last_name" value="<?= $user->last_name ?>">
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= $user->username ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</body>
</html>