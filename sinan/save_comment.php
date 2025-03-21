<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $user_id = 1; // Replace with actual user ID

    $sqlInsert = "INSERT INTO comments (post_id, user_id, comment) VALUES ('$post_id', '$user_id', '$comment')";
    if (mysqli_query($conn, $sqlInsert)) {
        header("Location: view.php?id=$post_id");
    } else {
        echo "Error: " . $sqlInsert . "<br>" . mysqli_error($conn);
    }
}
?>