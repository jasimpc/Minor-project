<?php
session_start();
include("connect.php");

if (!isset($_GET['topic_id'])) {
    die('Topic ID is required.');
}

$topic_id = mysqli_real_escape_string($conn, $_GET['topic_id']);
$sql = "SELECT * FROM post WHERE topic_id = '$topic_id'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topic Posts</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Bootstrap Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Link -->

    <!-- Font Awesome Cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Font Awesome Cdn -->

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
    <!-- Google Fonts -->
</head>
<body>
    <header class="p-4 bg-dark text-center">
        <h1><a href="homepage.php" class="text-light text-decoration-none">Gen<span>Z</span>Sphere</a></h1>
    </header>
    <div class="post-list mt-5">
        <div class="container">
            <?php
            while ($data = mysqli_fetch_array($result)) {
            ?>
            <div class="row mb-4 p-5 bg-light">
                <h4><?php echo $data["title"]; ?></h4>
                <div class="col-sm-2">
                
                    <?php if ($data["image"]) { ?>
                    <img src="../uploads/<?php echo $data["image"]; ?>" alt="<?php echo $data["title"]; ?>" class="img-fluid">
                    <?php } ?>
                    <?php echo $data["date"]; ?>
                </div>
                <div class="col-sm-5">
                    <?php echo $data["summary"]; ?>
                </div>
                <div class="col-sm-2">
                    <a href="view.php?id=<?php echo $data['id']; ?>" class="btn btn-primary">READ MORE</a>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="footer bg-dark p-4 mt-4 ">
        <a href="../admin/index.php" class="text-light text-decoration-none">Admin Panel</a>
    </div>
</body>
</html>