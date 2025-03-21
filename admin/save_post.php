<?php
include("../sinan/connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $summary = mysqli_real_escape_string($conn, $_POST['summary']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $topic_id = mysqli_real_escape_string($conn, $_POST['topic_id']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $image = '';

    // Handle image upload if necessary
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = basename($_FILES['image']['name']);
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    $sqlInsert = "INSERT INTO post (title, summary, content, topic_id, date, image) VALUES ('$title', '$summary', '$content', '$topic_id', '$date', '$image')";
    if (mysqli_query($conn, $sqlInsert)) {
        echo "Post created successfully";
    } else {
        echo "Error: " . $sqlInsert . "<br>" . mysqli_error($conn);
    }
}
?>