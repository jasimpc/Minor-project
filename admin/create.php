<?php
include("header.php");
?>

<div class="create-form w-100 mx-auto p-4" style="max-width:700px;">
    <form action="process.php" method="post" enctype="multipart/form-data">
        <div class="form-field mb-4">
            <input type="text" class="form-control" name="title" id="" placeholder="Enter Title:">
        </div>
        <div class="form-field mb-4">
            <textarea name="summary" class="form-control" id="" cols="30" rows="10" placeholder="Enter Summary:"></textarea>
        </div>
        <div class="form-field mb-4">
            <textarea name="content" class="form-control" id="" cols="30" rows="10" placeholder="Enter Post:"></textarea>
        </div>
        <div class="form-field mb-4">
            <input type="file" class="form-control" name="image" id="">
        </div>
        <input type="hidden" name="date" value="<?php echo date("Y/m/d"); ?>">
        <div class="form-group mb-4">
            <label for="topic_id">Topic</label>
            <select name="topic_id" id="topic_id" class="form-control">
                <?php
                include("../sinan/connect.php");
                $sqlTopics = "SELECT * FROM categories";
                $resultTopics = mysqli_query($conn, $sqlTopics);
                while ($topic = mysqli_fetch_assoc($resultTopics)) {
                    echo '<option value="' . $topic['id'] . '">' . $topic['name'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-field">
            <input type="submit" class="btn btn-primary" value="Submit" name="create">
        </div>
    </form>
</div>

<?php
include("footer.php");
?>