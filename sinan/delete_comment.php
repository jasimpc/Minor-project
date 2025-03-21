<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = mysqli_real_escape_string($conn, $_POST['comment_id']);
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);

    $sqlDelete = "DELETE FROM comments WHERE id = '$comment_id'";
    if (mysqli_query($conn, $sqlDelete)) {
        header("Location: view.php?id=$post_id");
    } else {
        echo "Error: " . $sqlDelete . "<br>" . mysqli_error($conn);
    }
}
?>