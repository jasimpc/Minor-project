<?php
session_start();
include("connect.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['id'])) {
    die("Post ID is required.");
}

$post_id = intval($_GET['id']);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$is_admin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;

// Fetch post details
$sqlPost = "SELECT * FROM posts WHERE id = $post_id";
$resultPost = mysqli_query($conn, $sqlPost);

if (!$resultPost) {
    die("Error fetching post: " . mysqli_error($conn));
}

$post = mysqli_fetch_assoc($resultPost);

if (!$post) {
    die("Post not found.");
}

// Fetch like/dislike status for the current user
$vote = null;
if ($user_id) {
    $sqlVote = "SELECT is_like FROM votes WHERE post_id = $post_id AND user_id = $user_id";
    $resultVote = mysqli_query($conn, $sqlVote);
    
    if (!$resultVote) {
        die("Error fetching vote: " . mysqli_error($conn));
    }
    
    $vote = mysqli_fetch_assoc($resultVote);
}

// Handle like/dislike submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if (!$user_id) {
        die("You must be logged in to vote.");
    }
    
    $action = $_POST['action']; // 'like' or 'dislike'
    $is_like = ($action == 'like') ? 1 : 0;

    if ($vote) {
        $sqlUpdate = "UPDATE votes SET is_like = $is_like WHERE post_id = $post_id AND user_id = $user_id";
        if (!mysqli_query($conn, $sqlUpdate)) {
            die("Error updating vote: " . mysqli_error($conn));
        }
    } else {
        $sqlInsert = "INSERT INTO votes (post_id, user_id, is_like) VALUES ($post_id, $user_id, $is_like)";
        if (!mysqli_query($conn, $sqlInsert)) {
            die("Error inserting vote: " . mysqli_error($conn));
        }
    }

    header("Location: view.php?id=$post_id");
    exit();
}

// Fetch total likes and dislikes
$likes = 0;
$dislikes = 0;

$sqlLikes = "SELECT COUNT(*) as likes FROM votes WHERE post_id = $post_id AND is_like = 1";
$resultLikes = mysqli_query($conn, $sqlLikes);

if ($resultLikes) {
    $likes = mysqli_fetch_assoc($resultLikes)['likes'];
} else {
    echo "Error fetching likes: " . mysqli_error($conn);
}

$sqlDislikes = "SELECT COUNT(*) as dislikes FROM votes WHERE post_id = $post_id AND is_like = 0";
$resultDislikes = mysqli_query($conn, $sqlDislikes);

if ($resultDislikes) {
    $dislikes = mysqli_fetch_assoc($resultDislikes)['dislikes'];
} else {
    echo "Error fetching dislikes: " . mysqli_error($conn);
}

// Fetch comments with replies
$sqlComments = "SELECT comments.*, logins.email 
                FROM comments 
                JOIN logins ON comments.user_id = logins.id 
                WHERE post_id = $post_id AND parent_comment_id IS NULL 
                ORDER BY comments.create_at DESC";
$resultComments = mysqli_query($conn, $sqlComments);

if (!$resultComments) {
    die("Error fetching comments: " . mysqli_error($conn));
}

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
        return round($timeDifference / 86400) . " days ago";
    }
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    if (!$user_id) {
        die("You must be logged in to comment.");
    }
    
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $parent_comment_id = isset($_POST['parent_comment_id']) ? intval($_POST['parent_comment_id']) : null;

    $sqlInsert = "INSERT INTO comments (post_id, user_id, comment, parent_comment_id) VALUES ('$post_id', '$user_id', '$comment', " . ($parent_comment_id ? "'$parent_comment_id'" : "NULL") . ")";
    if (!mysqli_query($conn, $sqlInsert)) {
        die("Error adding comment: " . mysqli_error($conn));
    }

    header("Location: view.php?id=$post_id");
    exit();
}

// Handle comment deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_comment_id'])) {
    if (!$user_id) {
        die("You must be logged in to delete comments.");
    }
    
    $comment_id = intval($_POST['delete_comment_id']);

    // Check if the user is the owner of the comment or an admin
    $sqlCheckComment = "SELECT * FROM comments WHERE id = '$comment_id' AND (user_id = '$user_id' OR '$is_admin' = 1)";
    $resultCheckComment = mysqli_query($conn, $sqlCheckComment);

    if (!$resultCheckComment) {
        die("Error checking comment ownership: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($resultCheckComment) > 0) {
        // Delete the comment
        $sqlDeleteComment = "DELETE FROM comments WHERE id = '$comment_id'";
        if (!mysqli_query($conn, $sqlDeleteComment)) {
            die("Error deleting comment: " . mysqli_error($conn));
        }
        header("Location: view.php?id=$post_id");
        exit();
    } else {
        die("You do not have permission to delete this comment.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
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
            background-color: #f9f9f9;
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
        .btn-reply {
            margin-top: 10px;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Post Section -->
        <div class="post-container">
            <h1><?php echo htmlspecialchars($post['title']); ?></h1>
            <?php if (!empty($post['image'])) { ?>
                <img src="../uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" class="post-image">
            <?php } ?>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <div class="post-time">
                Posted <?php echo timeAgo($post['date']); ?>
            </div>
        </div>

        <!-- Like/Dislike Section -->
        <div class="mt-4">
            <?php if ($user_id): ?>
                <form method="POST">
                    <button type="submit" name="action" value="like" class="btn btn-success <?php echo ($vote && $vote['is_like'] == 1) ? 'disabled' : ''; ?>">
                        Like (<?php echo $likes; ?>)
                    </button>
                    <button type="submit" name="action" value="dislike" class="btn btn-danger <?php echo ($vote && $vote['is_like'] == 0) ? 'disabled' : ''; ?>">
                        Dislike (<?php echo $dislikes; ?>)
                    </button>
                </form>
            <?php else: ?>
                <p class="text-muted">Log in to like or dislike this post</p>
            <?php endif; ?>
        </div>

        <!-- Comment Section -->
        <div class="comment-section">
            <h2>Comments</h2>
            
            <?php if ($user_id): ?>
                <form action="view.php?id=<?php echo $post_id; ?>" method="POST" class="mb-4">
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <div class="mb-3">
                        <textarea name="comment" class="form-control" rows="3" placeholder="Add a comment" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            <?php else: ?>
                <p class="text-muted">Log in to leave a comment</p>
            <?php endif; ?>

            <?php 
            if (mysqli_num_rows($resultComments) > 0) {
                while ($comment = mysqli_fetch_assoc($resultComments)) { 
                    // Verify all required fields exist
                    if (!isset($comment['id'], $comment['comment'], $comment['create_at'], $comment['email'])) {
                        continue; // Skip invalid comments
                    }
                    ?>
                    <div class="comment-card">
                        <div class="comment-email"><?php echo htmlspecialchars($comment['email']); ?></div>
                        <div class="comment-text"><?php echo htmlspecialchars($comment['comment']); ?></div>
                        <div class="post-time"><?php echo timeAgo($comment['create_at']); ?></div>

                        <?php if ($user_id == $comment['user_id'] || $is_admin): ?>
                            <!-- Delete Button -->
                            <form action="view.php?id=<?php echo $post_id; ?>" method="POST" class="d-inline">
                                <input type="hidden" name="delete_comment_id" value="<?php echo $comment['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        <?php endif; ?>

                        <?php if ($user_id): ?>
                            <!-- Reply Button -->
                            <button class="btn btn-sm btn-secondary btn-reply" onclick="toggleReplyForm(<?php echo $comment['id']; ?>)">Reply</button>

                            <!-- Reply Form -->
                            <div id="reply-form-<?php echo $comment['id']; ?>" class="reply-form" style="display: none;">
                                <form action="view.php?id=<?php echo $post_id; ?>" method="POST">
                                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                    <input type="hidden" name="parent_comment_id" value="<?php echo $comment['id']; ?>">
                                    <div class="mb-3">
                                        <textarea name="comment" class="form-control" rows="2" placeholder="Write a reply" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Submit Reply</button>
                                </form>
                            </div>
                        <?php endif; ?>

                        <!-- Display Replies -->
                        <?php
                        $sqlReplies = "SELECT comments.*, logins.email 
                                    FROM comments 
                                    JOIN logins ON comments.user_id = logins.id 
                                    WHERE parent_comment_id = {$comment['id']} 
                                    ORDER BY comments.create_at ASC";
                        $resultReplies = mysqli_query($conn, $sqlReplies);
                        
                        if ($resultReplies && mysqli_num_rows($resultReplies) > 0) {
                            while ($reply = mysqli_fetch_assoc($resultReplies)) { 
                                if (!isset($reply['id'], $reply['comment'], $reply['create_at'], $reply['email'], $reply['user_id'])) {
                                    continue; // Skip invalid replies
                                }
                                ?>
                                <div class="comment-card mt-2" style="margin-left: 20px;">
                                    <div class="comment-email"><?php echo htmlspecialchars($reply['email']); ?></div>
                                    <div class="comment-text"><?php echo htmlspecialchars($reply['comment']); ?></div>
                                    <div class="post-time"><?php echo timeAgo($reply['create_at']); ?></div>
                                    <?php if ($user_id == $reply['user_id'] || $is_admin): ?>
                                        <form action="view.php?id=<?php echo $post_id; ?>" method="POST" class="d-inline">
                                            <input type="hidden" name="delete_comment_id" value="<?php echo $reply['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php } 
                        } ?>
                    </div>
                <?php } 
            } else {
                echo "<p>No comments yet. Be the first to comment!</p>";
            } ?>
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