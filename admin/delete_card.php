<?php
$id = $_GET["id"];
if($id){
    include("../sinan/connect.php");
    $sqlDelete = "DELETE FROM cards WHERE id = $id";
    if(mysqli_query($conn, $sqlDelete)){
        session_start();
        $_SESSION["delete"] = "Card deleted successfully";
        header("Location: index.php");
    }else{
        die("Something went wrong. Data is not deleted");
    }
}else{
    echo "Card not found";
}
?>