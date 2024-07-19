<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/settings/profile') ?>" onclick="showSection('profile')">Profile Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/settings/email') ?>" onclick="showSection('email_password')">Change Email/Password</a>
                </li>
                <!-- Add more options here -->
                <li class="nav-item">
                    <a href="<?= base_url('/settings/layout') ?>" class="nav-link" onclick="showSection('layout')">Layout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
