<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = mysqli_real_escape_string($conn, $_POST['comment_id']);
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $is_admin = $_SESSION['is_admin']; // Assuming is_admin is stored in session

    // Check if the user is the owner of the comment or an admin
    $sqlCheckComment = "SELECT * FROM comments WHERE id = '$comment_id' AND (user_id = '$user_id' OR '$is_admin' = 1)";
    $resultCheckComment = mysqli_query($conn, $sqlCheckComment);

    if (mysqli_num_rows($resultCheckComment) > 0) {
        // Delete the comment
        $sqlDeleteComment = "DELETE FROM comments WHERE id = '$comment_id'";
        if (mysqli_query($conn, $sqlDeleteComment)) {
            header("Location: view.php?id=$post_id");
        } else {
            echo "Error: " . $sqlDeleteComment . "<br>" . mysqli_error($conn);
        }
    } else {
        die("You do not have permission to delete this comment.");
    }
}
?>