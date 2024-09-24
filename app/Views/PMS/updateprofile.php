<?php $pageTitle = 'Update Profile' ?>
<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Adjust the body margin to prevent content from being covered by the navbar */
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
        color: #333;
    }
    /* Custom styles for the header */
    header {
        background-color: #007bff; /* Primary color for the header */
        color: white;
        padding: 10px 0;
    }
    /* Style for the form container */
    .container {
        background-color: white; /* White background for form */
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-top: 20px;
    }
    /* Logo for the submit button */
    .btn-logo {
        background-image: url('<?php echo base_url('assets/img/logo.png'); ?>'); /* Replace with your logo URL */
        background-size: 20px 20px; /* Size of the logo */
        background-repeat: no-repeat;
        background-position: left center;
        padding-left: 30px; /* Padding to the left for the logo */
    }
</style>

<?php $user = auth()->user(); ?>

<!-- Header content -->
<header>
    <?php include 'header.php' ?>
</header>

<div class="container">
    <h1 class="mb-4">Update Profile</h1>
    <form action="<?= base_url("/update_profile") ?>" method="post">
        <input type="hidden" id="id" name="id" value="<?= $user->id ?>">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $user->email ?>" required>
        </div>
        <div class="mb-3">
            <label for="firstName" class="form-label">First Name:</label>
            <input type="text" class="form-control" id="firstName" name="firstName" value="<?= $user->firstName ?>" required>
        </div>
        <div class="mb-3">
            <label for="lastName" class="form-label">Last Name:</label>
            <input type="text" class="form-control" id="lastName" name="lastName" value="<?= $user->lastName ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= $user->username ?>" required>
        </div>
        <button type="submit" class="btn btn-primary btn-logo">Update</button>
    </form>
</div>
<script src="<?php echo base_url('/assets/js/main.js')?>"></script>