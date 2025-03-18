<?php
session_start();
include("../connect.php");
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