<?php
include("../connect.php");

// Fetch total posts
$sqlPosts = "SELECT COUNT(*) as total_posts FROM posts";
$resultPosts = mysqli_query($conn, $sqlPosts);
$postCount = mysqli_fetch_assoc($resultPosts)['total_posts'];

// Fetch total comments
$sqlComments = "SELECT COUNT(*) as total_comments FROM comments";
$resultComments = mysqli_query($conn, $sqlComments);
$commentCount = mysqli_fetch_assoc($resultComments)['total_comments'];

// Fetch total likes
$sqlLikes = "SELECT COUNT(*) as total_likes FROM votes WHERE is_like = 1";
$resultLikes = mysqli_query($conn, $sqlLikes);
$likeCount = mysqli_fetch_assoc($resultLikes)['total_likes'];

// Fetch total categories
$sqlCategories = "SELECT COUNT(*) as total_categories FROM categories";
$resultCategories = mysqli_query($conn, $sqlCategories);
$categoryCount = mysqli_fetch_assoc($resultCategories)['total_categories'];

// Return data as JSON
echo json_encode([
    'post_count' => $postCount,
    'comment_count' => $commentCount,
    'like_count' => $likeCount,
    'category_count' => $categoryCount,
]);
?>