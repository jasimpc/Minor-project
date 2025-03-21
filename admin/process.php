<?php
if (isset($_POST["create"])) {
    include("../sinan/connect.php");
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $summary = mysqli_real_escape_string($conn, $_POST["summary"]);
    $content = mysqli_real_escape_string($conn, $_POST["content"]);
    $date = mysqli_real_escape_string($conn, $_POST["date"]);
    $topic_id = mysqli_real_escape_string($conn, $_POST["topic_id"]);

    // Handle file upload
    $image = $_FILES["image"]["name"];
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    $sqlInsert = "INSERT INTO post(date, title, summary, content, topic_id, image) VALUES ('$date', '$title', '$summary', '$content', '$topic_id', '$image')";
    if(mysqli_query($conn, $sqlInsert)){
        session_start();
        $_SESSION["create"] = "Post added successfully";
        header("Location:index.php");
    }else{
        die("Data is not inserted!");
    }
}

if (isset($_POST["update"])) {
    include("../sinan/connect.php");
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $summary = mysqli_real_escape_string($conn, $_POST["summary"]);
    $content = mysqli_real_escape_string($conn, $_POST["content"]);
    $date = mysqli_real_escape_string($conn, $_POST["date"]);
    $topic_id = mysqli_real_escape_string($conn, $_POST["topic_id"]);
    $id = mysqli_real_escape_string($conn, $_POST["id"]);

    // Handle file upload
    $image = $_FILES["image"]["name"];
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    if ($image) {
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $sqlUpdate = "UPDATE post SET title = '$title', summary = '$summary', content = '$content', date = '$date', topic_id = '$topic_id', image = '$image' WHERE id = $id";
    } else {
        $sqlUpdate = "UPDATE post SET title = '$title', summary = '$summary', content = '$content', date = '$date', topic_id = '$topic_id' WHERE id = $id";
    }

    if(mysqli_query($conn, $sqlUpdate)){
        session_start();
        $_SESSION["update"] = "Post updated successfully";
        header("Location:index.php");
    }else{
        die("Data is not updated!");
    }
}
?>