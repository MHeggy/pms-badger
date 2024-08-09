<?= $pageTitle = "Logout"; ?>
<link rel="stylesheet" href="<?php echo base_url('/assets/css/logout.css')?>">
<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Header content -->
<header>
    <?php include 'header.php'; ?>
</header>

<!-- Logout modal -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h1>Logout</h1>
        <p>Are you sure you want to logout?</p>
        <div class="buttons">
            <button class="button" onclick="logout()">Logout</button>
            <button class="button" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<script src="<?php echo base_url('/assets/js/logout.js') ?>"></script>
</body>
</html>
