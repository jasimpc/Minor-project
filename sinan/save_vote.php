// save_vote.php
<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $vote = intval($_POST['vote']); // 1 for like, -1 for dislike
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Check if the user has already voted on this post
    $sqlCheckVote = "SELECT * FROM votes WHERE post_id = '$post_id' AND user_id = '$user_id'";
    $resultCheckVote = mysqli_query($conn, $sqlCheckVote);

    if (mysqli_num_rows($resultCheckVote) > 0) {
        // User has already voted, update the vote
        $sqlUpdateVote = "UPDATE votes SET vote = '$vote' WHERE post_id = '$post_id' AND user_id = '$user_id'";
        if (mysqli_query($conn, $sqlUpdateVote)) {
            header("Location: view.php?id=$post_id");
        } else {
            echo "Error: " . $sqlUpdateVote . "<br>" . mysqli_error($conn);
        }
    } else {
        // Insert the new vote
        $sqlInsertVote = "INSERT INTO votes (post_id, user_id, vote) VALUES ('$post_id', '$user_id', '$vote')";
        if (mysqli_query($conn, $sqlInsertVote)) {
            header("Location: view.php?id=$post_id");
        } else {
            echo "Error: " . $sqlInsertVote . "<br>" . mysqli_error($conn);
        }
    }
}
?>