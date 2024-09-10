<?php $pageTitle = 'My Profile' ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('/assets/css/main.css') ?>">

    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding-top: 50px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: bold;
        }
        .form-control {
            border-radius: 0;
        }
        .mb-3 {
            margin-bottom: 20px;
        }
        .profile-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
    </style>

<?php $user = auth()->user(); ?>

<header>
    <?php include 'header.php' ?>
</header>

<div class="container">
    <h1>User Profile</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" value="<?= $user->username ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name:</label>
                <input type="text" class="form-control" id="firstName" value="<?= $user->firstName ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name:</label>
                <input type="text" class="form-control" id="lastName" value="<?= $user->lastName ?>" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="group" class="form-label">Group:</label>
                <input type="text" class="form-control" id="group" value="<?= $user->group ?>" readonly>
            </div>
        </div>
    </div>
    <a href="<?= base_url('/update_profile/' . $user->id) ?>" class="profile-link">Need to change/update your profile? Click here</a>
    <script src="<?php echo base_url('/assets/js/main.js')?>"></script>
</div>
</body>
</html>