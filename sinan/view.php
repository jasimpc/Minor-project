<?php
session_start();
include("connect.php");

if (!isset($_GET['id'])) {
    die("Post ID is required.");
}

$post_id = intval($_GET['id']);
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
$is_admin = $_SESSION['is_admin']; // Assuming is_admin is stored in session

// Fetch post details
$sqlPost = "SELECT * FROM posts WHERE id = $post_id";
$resultPost = mysqli_query($conn, $sqlPost);
$post = mysqli_fetch_assoc($resultPost);

if (!$post) {
    die("Post not found.");
}

// Fetch like/dislike status for the current user
$sqlVote = "SELECT is_like FROM votes WHERE post_id = $post_id AND user_id = $user_id";
$resultVote = mysqli_query($conn, $sqlVote);
$vote = mysqli_fetch_assoc($resultVote);

// Handle like/dislike submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action']; // 'like' or 'dislike'

    if ($vote) {
        // Update existing vote
        $is_like = ($action == 'like') ? 1 : 0;
        $sqlUpdate = "UPDATE votes SET is_like = $is_like WHERE post_id = $post_id AND user_id = $user_id";
        mysqli_query($conn, $sqlUpdate);
    } else {
        // Insert new vote
        $is_like = ($action == 'like') ? 1 : 0;
        $sqlInsert = "INSERT INTO votes (post_id, user_id, is_like) VALUES ($post_id, $user_id, $is_like)";
        mysqli_query($conn, $sqlInsert);
    }

    // Redirect to refresh the page
    header("Location: view.php?id=$post_id");
    exit();
}

// Fetch total likes and dislikes
$sqlLikes = "SELECT COUNT(*) as likes FROM votes WHERE post_id = $post_id AND is_like = 1";
$resultLikes = mysqli_query($conn, $sqlLikes);
$likes = mysqli_fetch_assoc($resultLikes)['likes'];

$sqlDislikes = "SELECT COUNT(*) as dislikes FROM votes WHERE post_id = $post_id AND is_like = 0";
$resultDislikes = mysqli_query($conn, $sqlDislikes);
$dislikes = mysqli_fetch_assoc($resultDislikes)['dislikes'];

// Fetch comments with replies
$sqlComments = "SELECT comments.*, users.email 
                FROM comments 
                JOIN users ON comments.user_id = users.id 
                WHERE post_id = $post_id AND parent_comment_id IS NULL 
                ORDER BY comments.created_at DESC";
$resultComments = mysqli_query($conn, $sqlComments);

// Function to calculate time ago
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $currentTime = time();
    $timeDifference = $currentTime - $time;

    if ($timeDifference < 60) {
        return "$timeDifference seconds ago";
    } elseif ($timeDifference < 3600) {
        return round($timeDifference / 60) . " minutes ago";
    } elseif ($timeDifference < 86400) {
        return round($timeDifference / 3600) . " hours ago";
    } else {
        return date('Y-m-d H:i', $time);
    }
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $parent_comment_id = isset($_POST['parent_comment_id']) ? intval($_POST['parent_comment_id']) : null;

    $sqlInsert = "INSERT INTO comments (post_id, user_id, comment, parent_comment_id) VALUES ('$post_id', '$user_id', '$comment', " . ($parent_comment_id ? "'$parent_comment_id'" : "NULL") . ")";
    if (mysqli_query($conn, $sqlInsert)) {
        header("Location: view.php?id=$post_id");
        exit();
    } else {
        echo "Error: " . $sqlInsert . "<br>" . mysqli_error($conn);
    }
}

// Handle comment deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_comment_id'])) {
    $comment_id = intval($_POST['delete_comment_id']);

    // Check if the user is the owner of the comment or an admin
    $sqlCheckComment = "SELECT * FROM comments WHERE id = '$comment_id' AND (user_id = '$user_id' OR '$is_admin' = 1)";
    $resultCheckComment = mysqli_query($conn, $sqlCheckComment);

    if (mysqli_num_rows($resultCheckComment) > 0) {
        // Delete the comment
        $sqlDeleteComment = "DELETE FROM comments WHERE id = '$comment_id'";
        if (mysqli_query($conn, $sqlDeleteComment)) {
            header("Location: view.php?id=$post_id");
            exit();
        } else {
            echo "Error: " . $sqlDeleteComment . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "You do not have permission to delete this comment.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post['title']; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .post-container {
            border: 2px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .post-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .post-time {
            font-size: 0.9em;
            color: #666;
            margin-top: 10px;
        }
        .comment-section {
            margin-top: 20px;
        }
        .comment-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .comment-email {
            font-size: 0.8em;
            color: #888;
            margin-bottom: 5px;
        }
        .comment-text {
            font-size: 1em;
            color: #333;
        }
        .reply-form {
            margin-top: 10px;
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Post Section -->
        <div class="post-container">
            <h1><?php echo $post['title']; ?></h1>
            <?php if (!empty($post['image'])) { ?>
                <img src="<?php echo $post['image']; ?>" alt="Post Image" class="post-image">
            <?php } ?>
            <p><?php echo $post['content']; ?></p>
            <div class="post-time">
                Posted <?php echo timeAgo($post['date']); ?>
            </div>
        </div>

        <!-- Like/Dislike Section -->
        <div class="mt-4">
            <form method="POST">
                <button type="submit" name="action" value="like" class="btn btn-success <?php echo ($vote && $vote['is_like'] == 1) ? 'disabled' : ''; ?>">
                    Like (<?php echo $likes; ?>)
                </button>
                <button type="submit" name="action" value="dislike" class="btn btn-danger <?php echo ($vote && $vote['is_like'] == 0) ? 'disabled' : ''; ?>">
                    Dislike (<?php echo $dislikes; ?>)
                </button>
            </form>
        </div>

        <!-- Comment Section -->
        <div class="comment-section">
            <h2>Comments</h2>
            <form action="view.php?id=<?php echo $post_id; ?>" method="POST" class="mb-4">
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <div class="mb-3">
                    <textarea name="comment" class="form-control" rows="3" placeholder="Add a comment"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            <?php while ($comment = mysqli_fetch_assoc($resultComments)) { ?>
                <div class="comment-card">
                    <?php if ($is_admin) { ?>
                        <div class="comment-email"><?php echo $comment['email']; ?></div>
                    <?php } ?>
                    <div class="comment-text"><?php echo $comment['comment']; ?></div>
                    <div class="post-time"><?php echo timeAgo($comment['created_at']); ?></div>

                    <!-- Reply Button -->
                    <button class="btn btn-sm btn-secondary mt-2" onclick="toggleReplyForm(<?php echo $comment['id']; ?>)">Reply</button>

                    <!-- Reply Form -->
                    <div id="reply-form-<?php echo $comment['id']; ?>" class="reply-form" style="display: none;">
                        <form action="view.php?id=<?php echo $post_id; ?>" method="POST">
                            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                            <input type="hidden" name="parent_comment_id" value="<?php echo $comment['id']; ?>">
                            <div class="mb-3">
                                <textarea name="comment" class="form-control" rows="2" placeholder="Write a reply"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Submit Reply</button>
                        </form>
                    </div>

                    <!-- Display Replies -->
                    <?php
                    $sqlReplies = "SELECT comments.*, users.email 
                                   FROM comments 
                                   JOIN users ON comments.user_id = users.id 
                                   WHERE parent_comment_id = {$comment['id']} 
                                   ORDER BY comments.created_at ASC";
                    $resultReplies = mysqli_query($conn, $sqlReplies);
                    while ($reply = mysqli_fetch_assoc($resultReplies)) { ?>
                        <div class="comment-card mt-2" style="margin-left: 20px;">
                            <?php if ($is_admin) { ?>
                                <div class="comment-email"><?php echo $reply['email']; ?></div>
                            <?php } ?>
                            <div class="comment-text"><?php echo $reply['comment']; ?></div>
                            <div class="post-time"><?php echo timeAgo($reply['created_at']); ?></div>
                            <?php if ($reply['user_id'] == $user_id || $is_admin) { ?>
                                <form action="view.php?id=<?php echo $post_id; ?>" method="POST" class="d-inline">
                                    <input type="hidden" name="delete_comment_id" value="<?php echo $reply['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        // Function to toggle reply form
        function toggleReplyForm(commentId) {
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>