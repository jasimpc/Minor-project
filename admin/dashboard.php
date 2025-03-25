<?php
include("header.php");
?>

<div class="container-fluid p-5">
    <h1 class="text-center mb-5">Admin Dashboard - Real-Time Counts</h1>
    <div class="row" id="counts-container">
        <!-- Counts will be dynamically loaded here -->
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white text-center p-4">
                <div class="card-body">
                    <h5 class="card-title">Total Posts</h5>
                    <p class="card-text display-4" id="post-count">Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white text-center p-4">
                <div class="card-body">
                    <h5 class="card-title">Total Comments</h5>
                    <p class="card-text display-4" id="comment-count">Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white text-center p-4">
                <div class="card-body">
                    <h5 class="card-title">Total Likes</h5>
                    <p class="card-text display-4" id="like-count">Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white text-center p-4">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <p class="card-text display-4" id="category-count">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- AJAX Script for Real-Time Updates -->
<script>
    function fetchCounts() {
        $.ajax({
            url: "fetch_counts.php", // PHP file to fetch counts
            method: "GET",
            dataType: "json",
            success: function(data) {
                $("#post-count").text(data.post_count);
                $("#comment-count").text(data.comment_count);
                $("#like-count").text(data.like_count);
                $("#category-count").text(data.category_count);
            },
            error: function() {
                console.log("Error fetching counts.");
            }
        });
    }

    // Fetch counts every 5 seconds
    setInterval(fetchCounts, 5000);

    // Initial fetch
    fetchCounts();
</script>

<?php include("footer.php"); ?>