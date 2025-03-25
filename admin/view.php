<?php
include("header.php");
?>
<div class="post w-100 p-5" style="margin-left: 28px;">
    <?php
    $id = $_GET["id"];
    if ($id) {
        include("../sinan/connect.php");
        $sqlSelectPost = "SELECT * FROM posts WHERE id = $id";
        $result = mysqli_query($conn, $sqlSelectPost);
        while ($data = mysqli_fetch_array($result)) {
        ?>
        <h1><?php echo  $data['title']; ?></h1>
        <p><?php echo $data['date']; ?></p>
        <?php if ($data["image"]) { ?>
            <img src="../uploads/<?php echo $data["image"]; ?>" alt="<?php echo $data["title"]; ?>" class="img-fluid">
        <?php } ?>
        <p><?php echo $data['content']; ?></p>
        <?php
        }
    } else {
        echo "Post Not Found";
    }
    ?>
</div>

<?php
include("footer.php");
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
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