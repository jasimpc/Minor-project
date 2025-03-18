<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $created_by = $_SESSION['user_id'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/"; // Directory to store uploaded images
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Create the directory if it doesn't exist
        }

        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Insert the new card into the database with the image path
                $stmt = $conn->prepare("INSERT INTO cards (name, description, created_by, image_path) VALUES (?, ?, ?, ?)");
                if ($stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }
                $stmt->bind_param("ssis", $name, $description, $created_by, $target_file);
                if ($stmt->execute()) {
                    // Redirect to card.php after creating the card
                    header("Location: card.php");
                    exit();
                } else {
                    die('Execute failed: ' . htmlspecialchars($stmt->error));
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        echo "No image uploaded or an error occurred.";
    }
} else {
    echo "Invalid request method.";
}
?>