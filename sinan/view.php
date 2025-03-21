<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Blog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.1/emojionearea.min.css">
    <style>
        .comments-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .comment {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .comment:last-child {
            border-bottom: none;
        }
        .comment-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
    </style>
</head>
<body>
    <header class="p-4 bg-dark text-center">
        <h1><a href="index.php" class="text-light text-decoration-none"> Blog</a></h1>
    </header>
    <div class="post-list mt-5">
        <div class="container">
            <?php
                session_start();
                include("connect.php"); // Ensure this line is present and correct
                $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
                $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                if ($id) {
                    $sqlSelect = "SELECT * FROM post WHERE id = $id";
                    $result = mysqli_query($conn, $sqlSelect);
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($data = mysqli_fetch_array($result)) {
                        ?>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="post bg-light p-4 mt-5">
                                    <h2 class="fw-bold"><?php echo $data['title']; ?></h2>
                                    <?php if ($data["image"]) { 
                                        $imagePath = "uploads/" . $data["image"];
                                        ?>
                                        <img src="<?php echo $imagePath; ?>" class="img-fluid pt-2">
                                        
                                        <?php if (!file_exists($imagePath)) { ?>
                                            <p class="text-danger">Image file does not exist.</p>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <p class="text-warning">No image available for this post.</p>
                                    <?php } ?>
                                    <p class="pt-4"><?php echo $data['content']; ?> </p>
                                    <p class="text-muted small "><?php echo $data['date']; ?> </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="votes mt-5">
                                    <h3>Vote</h3>
                                    <?php
                                        // Check if the user has already voted
                                        $sqlCheckVote = "SELECT vote FROM votes WHERE user_id = $user_id AND post_id = $id";
                                        $resultCheckVote = mysqli_query($conn, $sqlCheckVote);
                                        $userVote = mysqli_fetch_array($resultCheckVote);
                                        if ($userVote) {
                                            echo '<p>You have already voted.</p>';
                                        } else {
                                    ?>
                                    <form action="save_vote.php" method="post">
                                        <input type="hidden" name="post_id" value="<?php echo $id; ?>">
                                        <button type="submit" name="vote" value="1" class="btn btn-success btn-sm">Upvote</button>
                                        <button type="submit" name="vote" value="-1" class="btn btn-danger btn-sm">Downvote</button>
                                    </form>
                                    <?php
                                        }
                                        // Fetch the vote count from the database
                                        $sqlVotes = "SELECT SUM(vote) as vote_count FROM votes WHERE post_id = $id";
                                        $resultVotes = mysqli_query($conn, $sqlVotes);
                                        $voteData = mysqli_fetch_array($resultVotes);
                                        $voteCount = $voteData['vote_count'] ? $voteData['vote_count'] : 0;
                                    ?>
                                    <p class="mt-2">Votes: <?php echo $voteCount; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                    } else {
                        echo "No post found";
                    }
                } else {
                    echo "No post found";
                }
            ?>        
            <div class="comments-container mt-5">
                <h3>Comments</h3>
                <?php
                $sqlComments = "SELECT * FROM comments WHERE post_id = $id";
                $resultComments = mysqli_query($conn, $sqlComments);
                while ($comment = mysqli_fetch_array($resultComments)) {
                    // Fetch the like and dislike count for each comment
                    $comment_id = $comment['id'];
                    $sqlCommentVotes = "SELECT SUM(vote) as vote_count FROM comment_vote WHERE comment_id = $comment_id";
                    $resultCommentVotes = mysqli_query($conn, $sqlCommentVotes);
                    $commentVoteData = mysqli_fetch_array($resultCommentVotes);
                    $commentVoteCount = $commentVoteData['vote_count'] ? $commentVoteData['vote_count'] : 0;

                    echo '<div class="comment">';
                    echo '<p>' . $comment['comment'] . '</p>';
                    echo '<small>' . $comment['create_at'] . '</small>';
                    echo '<div class="comment-actions">';
                    echo '<form action="save_comment_vote.php" method="post" class="d-inline">';
                    echo '<input type="hidden" name="comment_id" value="' . $comment['id'] . '">';
                    echo '<input type="hidden" name="post_id" value="' . $id . '">';
                    echo '<button type="submit" name="vote" value="1" class="btn btn-success btn-sm">👍</button>';
                    echo '</form>';
                    echo '<form action="save_comment_vote.php" method="post" class="d-inline">';
                    echo '<input type="hidden" name="comment_id" value="' . $comment['id'] . '">';
                    echo '<input type="hidden" name="post_id" value="' . $id . '">';
                    echo '<button type="submit" name="vote" value="-1" class="btn btn-danger btn-sm">👎</button>';
                    echo '</form>';
                    echo '<p class="mt-2">Votes: ' . $commentVoteCount . '</p>';
                    echo '</div>';
                    echo '<form action="delete_comment.php" method="post" class="mt-2">';
                    echo '<input type="hidden" name="comment_id" value="' . $comment['id'] . '">';
                    echo '<input type="hidden" name="post_id" value="' . $id . '">';
                    echo '<button type="submit" class="btn btn-danger btn-sm">Delete</button>';
                    echo '</form>';
                    echo '</div>';
                }
                ?>
                <form action="save_comment.php" method="post">
                    <div class="form-group">
                        <textarea name="comment" class="form-control" placeholder="Add a comment"></textarea>
                    </div>
                    <input type="hidden" name="post_id" value="<?php echo $id; ?>">
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <div class="footer bg-dark p-4 mt-4">
        <a href="../admin/index.php" class="text-light">Admin Panel</a>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.1/emojionearea.min.js"></script>
    <script>
        $(document).ready(function() {
            $("textarea[name='comment']").emojioneArea({
                pickerPosition: "bottom"
            });
        });
    </script>
</body>
</html>