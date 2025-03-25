<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('location:../sinan/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            position: fixed;
            width: 20%;
            height: 100vh;
            background-color: #343a40;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 20%;
            padding: 20px;
        }
        .dashboard-icon {
            position: fixed;
            top: 20px;
            right: 20px;
            font-size: 24px;
            color: #007bff;
            z-index: 1000;
        }
        .dashboard-icon:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Dashboard Icon (Top Right Corner) -->
    <a href="dashboard.php" class="dashboard-icon">
        <i class="fas fa-home"></i> <!-- Font Awesome home icon -->
    </a>

    <div class="sidebar">
        <h1 class="bg-primary p-4">
            <a href="index.php" class="text-light text-decoration-none">Dashboard</a>
        </h1>
        <div class="menues p-4 mt-5">
        <div class="menu">
                <a href="../sinan/homepage.php">View Web</a>
            </div>
           
            <div class="menu">
                <a href="create.php">Posts</a>
            </div>
            <div class="menu mt-4">
                <a href="card.php">Topics</a>
            </div>
            <div class="menu mt-4">
                <a href="../sinan/logout.php" class="btn btn-info">Logout</a>
            </div>
        </div>
    </div>
    <div class="content">
        <!-- Content will be loaded here -->
        <?php
        if (isset($_SESSION["create"])) {
            echo '<div class="alert alert-success">' . $_SESSION["create"] . '</div>';
            unset($_SESSION["create"]);
        }
        if (isset($_SESSION["update"])) {
            echo '<div class="alert alert-success">' . $_SESSION["update"] . '</div>';
            unset($_SESSION["update"]);
        }
        if (isset($_SESSION["delete"])) {
            echo '<div class="alert alert-success">' . $_SESSION["delete"] . '</div>';
            unset($_SESSION["delete"]);
        }
        ?>