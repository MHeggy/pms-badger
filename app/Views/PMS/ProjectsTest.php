<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $pageTitle = "3D Projects Test" ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>

    <div id="container"></div>
    <script src="<?php echo base_url('/assets/js/three.js') ?>"></script>
</body>
</html>