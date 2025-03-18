<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['email'])) {
    header('location:../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashBoard</title>
    <!-- Bootstrap Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Bootstrap Link -->
</head>
<body>
    <div class="dashboard d-flex justify-content-between">
        <div class="sidebar bg-dark vh-100" style='position:fixed; width:20%;'>
            <h1 class="bg-primary p-4">
                <a href="/cms-php-mysql-main/admin/index.php" class="text-light text-decoration-none">DashBoard</a>
            </h1>
            <div class="menues p-4 mt-5">
                <div class="menu">
                    <a href="/cms-php-mysql-main/admin/create.php" class="text-light text-decoration-none"><strong>ADD NEW POST</strong></a>
                </div>
                <div class="menu mt-5">
                    <a href="/cms-php-mysql-main/homepage.php" class="text-light text-decoration-none"><strong>VIEW WEBSITE</strong></a>
                </div>
                <div class="menu mt-5">
                    <a href="/cms-php-mysql-main/card.php" class="text-light text-decoration-none"><strong>CARD CREATE</strong></a>
                </div>
                <div class="menu mt-4">
                    <a href="/cms-php-mysql-main/admin/logout.php" class="btn btn-info">Logout</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>