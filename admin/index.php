<?php
session_start();
include("../sinan/connect.php");

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    die('Access denied. Only admins can access this page.');
}
?>

<?php
include("header.php");
?>

<div class="posts-list w-100 p-5">
    <?php if (isset($_SESSION["create"])): ?>
    <div class="alert alert-success alert-lightbox" id="createAlert">
        <?php echo $_SESSION["create"]; ?>
    </div>
    <?php unset($_SESSION["create"]); endif; ?>

    <?php if (isset($_SESSION["update"])): ?>
    <div class="alert alert-success alert-lightbox" id="updateAlert">
        <?php echo $_SESSION["update"]; ?>
    </div>
    <?php unset($_SESSION["update"]); endif; ?>

    <?php if (isset($_SESSION["delete"])): ?>
    <div class="alert alert-success alert-lightbox" id="deleteAlert">
        <?php echo $_SESSION["delete"]; ?>
    </div>
    <?php unset($_SESSION["delete"]); endif; ?>

    <h2 style="padding-left:25px">Posts</h2>
    <table class="table table-bordered" style="width:90%; margin-left:250px;">
        <thead>
            <tr>
                <th style="width:15%;">Publication Date</th>
                <th style="width:15%;">Title</th>
                <th style="width:45%;">Article</th>
                <th style="width:25%;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sqlSelect = "SELECT * FROM post";
            $result = mysqli_query($conn, $sqlSelect);
            while($data = mysqli_fetch_array($result)){
            ?>
            <tr>
                <td><?php echo $data["date"]?></td>
                <td><?php echo $data["title"]?></td>
                <td><?php echo $data["summary"]?></td>
                <td>
                    <a class="btn btn-info" href="view.php?id=<?php echo $data["id"]?>">View</a>
                    <a class="btn btn-warning" href="edit.php?id=<?php echo $data["id"]?>">Edit</a>
                    <a class="btn btn-danger" href="delete.php?id=<?php echo $data["id"]?>">Delete</a>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <h2 style="padding-left:25px">Cards</h2>
    <table class="table table-bordered" style="width:90%; margin-left:250px;">
        <thead>
            <tr>
                <th style="width:15%;">Created At</th>
                <th style="width:15%;">Name</th>
                <th style="width:45%;">Description</th>
                <th style="width:25%;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sqlSelectCards = "SELECT * FROM cards";
            $resultCards = mysqli_query($conn, $sqlSelectCards);
            while($card = mysqli_fetch_array($resultCards)){
            ?>
            <tr>
                <td><?php echo $card["created_at"]?></td>
                <td><?php echo $card["name"]?></td>
                <td><?php echo $card["description"]?></td>
                <td>
                    <a class="btn btn-info" href="../sinan/topic.php?topic_id=<?php echo $card["id"]?>">View</a>
                    <a class="btn btn-warning" href="edit_card.php?id=<?php echo $card["id"]?>">Edit</a>
                    <a class="btn btn-danger" href="delete_card.php?id=<?php echo $card["id"]?>">Delete</a>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include("footer.php");
?>

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