<?php
include("../sinan/connect.php");

// Fetch total posts
$post_count_query = "SELECT COUNT(*) as post_count FROM posts";
$post_count_result = mysqli_query($conn, $post_count_query);
$post_count = mysqli_fetch_assoc($post_count_result)['post_count'];

// Fetch total comments
$comment_count_query = "SELECT COUNT(*) as comment_count FROM comments";
$comment_count_result = mysqli_query($conn, $comment_count_query);
$comment_count = mysqli_fetch_assoc($comment_count_result)['comment_count'];

// Fetch total likes
$like_count_query = "SELECT COUNT(*) as like_count FROM likes";
$like_count_result = mysqli_query($conn, $like_count_query);
$like_count = mysqli_fetch_assoc($like_count_result)['like_count'];

// Fetch total categories
$category_count_query = "SELECT COUNT(*) as category_count FROM categories";
$category_count_result = mysqli_query($conn, $category_count_query);
$category_count = mysqli_fetch_assoc($category_count_result)['category_count'];

// Return counts as JSON
echo json_encode([
    'post_count' => $post_count,
    'comment_count' => $comment_count,
    'like_count' => $like_count,
    'category_count' => $category_count
]);

mysqli_close($conn);
?>