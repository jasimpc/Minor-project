<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Blog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <header class="p-4 bg-dark text-center">
        <h1><a href="index.php" class="text-light text-decoration-none">Simple Blog</a></h1>
    </header>
    <div class="post-list mt-5">
        <div class="container">
            <form method="GET" action="index.php" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <select name="topic_id" class="form-select">
                            <option value="">All Topics</option>
                            <?php
                            include("connect.php");
                            $sqlTopics = "SELECT * FROM cards";
                            $resultTopics = mysqli_query($conn, $sqlTopics);
                            while ($topic = mysqli_fetch_assoc($resultTopics)) {
                                echo '<option value="' . $topic['id'] . '">' . $topic['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
            <?php
                include("connect.php");
                $topic_id = isset($_GET['topic_id']) ? mysqli_real_escape_string($conn, $_GET['topic_id']) : '';
                $sqlSelect = "SELECT * FROM post";
                if ($topic_id) {
                    $sqlSelect .= " WHERE topic_id = '$topic_id'";
                }
                $result = mysqli_query($conn, $sqlSelect);
                while ($data = mysqli_fetch_array($result)) {
                ?>
                    <div class="row mb-4 p-5 bg-light">
                        <div class="col-sm-2">
                             <?php if ($data["image"]) { ?>
                                  <img src="uploads/<?php echo $data["image"]; ?>" alt="<?php echo $data["title"]; ?>" class="img-fluid">
                             <?php } ?>
                             <?php echo $data["date"]; ?>
                        </div>
                        <div class="col-sm-5">
                            <?php echo $data["content"]; ?>
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