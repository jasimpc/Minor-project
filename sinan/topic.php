<?php
session_start();
include("connect.php");

if (!isset($_GET['topic_id'])) {
    die('Topic ID is required.');
}

$topic_id = mysqli_real_escape_string($conn, $_GET['topic_id']);
$sql = "SELECT * FROM posts WHERE topic_id = '$topic_id'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topic Posts</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <header class="p-4 bg-dark text-center">
        <h1><a href="homepage.php" class="text-light text-decoration-none">Gen<span>Z</span>Sphere</a></h1>
    </header>

    <div class="container mt-5">
        <div class="row">
            <?php
            while ($data = mysqli_fetch_array($result)) {
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if ($data["image"]) { ?>
                            <img src="../admin/uploads/<?php echo $data["image"]; ?>" class="card-img-top" alt="<?php echo $data["title"]; ?>">
                        <?php } ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $data["title"]; ?></h5>
                            <p class="card-text"><?php echo $data["summary"]; ?></p>
                            <p class="card-text"><small class="text-muted"><?php echo $data["date"]; ?></small></p>
                        </div>
                        <div class="card-footer">
                            <a href="view.php?id=<?php echo $data['id']; ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="footer">
        <a href="../admin/index.php" class="text-light text-decoration-none">Admin Panel</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>