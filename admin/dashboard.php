<?php
include("header.php");
include("../sinan/connect.php");

// Fetch counts from the database
$sqlPostCount = "SELECT COUNT(*) as post_count FROM posts";
$resultPostCount = mysqli_query($conn, $sqlPostCount);
$postCount = mysqli_fetch_assoc($resultPostCount)['post_count'];

$sqlCommentCount = "SELECT COUNT(*) as comment_count FROM comments";
$resultCommentCount = mysqli_query($conn, $sqlCommentCount);
$commentCount = mysqli_fetch_assoc($resultCommentCount)['comment_count'];

$sqlLikeCount = "SELECT COUNT(*) as like_count FROM votes WHERE is_like = 1";
$resultLikeCount = mysqli_query($conn, $sqlLikeCount);
$likeCount = mysqli_fetch_assoc($resultLikeCount)['like_count'];

$sqlCategoryCount = "SELECT COUNT(*) as category_count FROM categories";
$resultCategoryCount = mysqli_query($conn, $sqlCategoryCount);
$categoryCount = mysqli_fetch_assoc($resultCategoryCount)['category_count'];
?>

<div class="container-fluid p-5">
    <h1 class="text-center mb-5">Admin Dashboard - Real-Time Counts</h1>
    <div class="row" id="counts-container">
        <!-- Counts will be dynamically loaded here -->
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white text-center p-4">
                <div class="card-body">
                    <h5 class="card-title">Total Posts</h5>
                    <p class="card-text display-4 count" id="post-count"><?php echo $postCount; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white text-center p-4">
                <div class="card-body">
                    <h5 class="card-title">Total Comments</h5>
                    <p class="card-text display-4 count" id="comment-count"><?php echo $commentCount; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white text-center p-4">
                <div class="card-body">
                    <h5 class="card-title">Total Likes</h5>
                    <p class="card-text display-4 count" id="like-count"><?php echo $likeCount; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white text-center p-4">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <p class="card-text display-4 count" id="category-count"><?php echo $categoryCount; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .count {
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }
    .count.loaded {
        opacity: 1;
    }
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .card-body {
        padding: 30px;
    }
    .card-title {
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
    .card-text {
        font-size: 2.5rem;
        font-weight: bold;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const counts = document.querySelectorAll(".count");
        counts.forEach(count => {
            count.classList.add("loaded");
        });
    });
</script>

<?php include("footer.php"); ?>