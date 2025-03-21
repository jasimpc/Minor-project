<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = mysqli_real_escape_string($conn, $_POST['comment_id']);
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $vote = intval($_POST['vote']); // Ensure vote is an integer
    $user_id = 1; // Replace with actual user ID from session or other source

    // Check if the user has already voted on this comment
    $sqlCheckVote = "SELECT * FROM comment_vote WHERE comment_id = '$comment_id' AND user_id = '$user_id'";
    $resultCheckVote = mysqli_query($conn, $sqlCheckVote);

    if (mysqli_num_rows($resultCheckVote) > 0) {
        // User has already voted, update the vote
        $sqlUpdateVote = "UPDATE comment_vote SET vote = '$vote' WHERE comment_id = '$comment_id' AND user_id = '$user_id'";
        if (mysqli_query($conn, $sqlUpdateVote)) {
            header("Location: view.php?id=$post_id");
        } else {
            echo "Error: " . $sqlUpdateVote . "<br>" . mysqli_error($conn);
        }
    } else {
        // Insert the new vote
        $sqlInsertVote = "INSERT INTO comment_vote (comment_id, user_id, vote) VALUES ('$comment_id', '$user_id', '$vote')";
        if (mysqli_query($conn, $sqlInsertVote)) {
            header("Location: view.php?id=$post_id");
        } else {
            echo "Error: " . $sqlInsertVote . "<br>" . mysqli_error($conn);
        }
    }
}
?>