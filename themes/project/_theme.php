<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <?php include 'includes/head.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<!-- Header -->
<?php include 'includes/sidebar.php'; ?>

<?php include 'includes/header.php'; ?>
<!-- Header -->
<!-- Main Content -->
<?= $this->section('content'); ?>
<!-- Main Content -->

<!-- Footer -->
<?php include 'includes/footer.php'; ?>
<!-- Footer -->

</body>
</html>