<?php
session_start();
include("../sinan/connect.php");
?>

<?php include("header.php"); ?>

<div class="container-fluid p-5">
    <h1 class="text-center mb-5">Admin Panel</h1>

    <!-- Tabs for Posts and Topics -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab" aria-controls="posts" aria-selected="true">Posts</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="cards-tab" data-bs-toggle="tab" data-bs-target="#cards" type="button" role="tab" aria-controls="cards" aria-selected="false">Topics</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="myTabContent">
        <!-- Posts Tab -->
        <div class="tab-pane fade show active" id="posts" role="tabpanel" aria-labelledby="posts-tab">
            <h2 class="mt-5">Posts</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                $sqlSelect = "SELECT * FROM posts";
                $result = mysqli_query($conn, $sqlSelect);
                while ($data = mysqli_fetch_array($result)) {
                ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $data["title"]; ?></h5>
                                <p class="card-text"><?php echo $data["summary"]; ?></p>
                                <p class="card-text"><small class="text-muted"><?php echo $data["date"]; ?></small></p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a class="btn btn-info btn-sm" href="view.php?id=<?php echo $data["id"]; ?>">View</a>
                                <a class="btn btn-warning btn-sm" href="edit.php?id=<?php echo $data["id"]; ?>">Edit</a>
                                <a class="btn btn-danger btn-sm" href="delete.php?id=<?php echo $data["id"]; ?>">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

        <!-- Topic Tab -->
        <div class="tab-pane fade" id="cards" role="tabpanel" aria-labelledby="cards-tab">
            <h2 class="mt-5">Topics</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                $sqlSelectCards = "SELECT * FROM categories";
                $resultCards = mysqli_query($conn, $sqlSelectCards);
                while ($card = mysqli_fetch_array($resultCards)) {
                ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $card["name"]; ?></h5>
                                <p class="card-text"><?php echo $card["description"]; ?></p>
                                <p class="card-text"><small class="text-muted"><?php echo $card["created_at"]; ?></small></p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a class="btn btn-info btn-sm" href="edit_card.php?id=<?php echo $card["id"]; ?>">Edit</a>
                                <a class="btn btn-danger btn-sm" href="delete_card.php?id=<?php echo $card["id"]; ?>">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        var createAlert = document.getElementById("createAlert");
        if (createAlert) {
            createAlert.style.display = "none";
        }
        var updateAlert = document.getElementById("updateAlert");
        if (updateAlert) {
            updateAlert.style.display = "none";
        }
        var deleteAlert = document.getElementById("deleteAlert");
        if (deleteAlert) {
            deleteAlert.style.display = "none";
        }
    }, 10000); // 10 seconds

    let sidebar = document.querySelector(".sidebar");
    document.addEventListener("mousemove", function(event) {
        if (event.clientX < 50) {
            sidebar.style.left = "0";
        } else if (event.clientX > 200) {
            sidebar.style.left = "-200px";
        }
    });
});
</script>