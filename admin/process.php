<?php
session_start(); // Start the session at the beginning

include("../sinan/connect.php");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (isset($_POST["create"])) {
    echo "Form submitted for creation.<br>"; // Debugging

    // Sanitize inputs
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $summary = mysqli_real_escape_string($conn, $_POST["summary"]);
    $content = mysqli_real_escape_string($conn, $_POST["content"]);
    $date = mysqli_real_escape_string($conn, $_POST["date"]);
    $topic_id = isset($_POST["topic_id"]) ? mysqli_real_escape_string($conn, $_POST["topic_id"]) : null;
    echo "Inputs sanitized.<br>"; // Debugging

    // Handle file upload
    $image = isset($_FILES["image"]["name"]) ? $_FILES["image"]["name"] : null;
    if ($image) {
        echo "File uploaded: $image<br>"; // Debugging
        $target_dir = "../sinan/uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($image);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "File moved to $target_file.<br>"; // Debugging
        } else {
            die("File upload failed!");
        }
    }

    // Prepare the SQL statement for creation
    if ($image) {
        $stmt = $conn->prepare("INSERT INTO posts (title, summary, content, date, topic_id, image) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssss", $title, $summary, $content, $date, $topic_id, $image);
        } else {
            die("Error preparing statement: " . $conn->error);
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO posts (title, summary, content, date, topic_id) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssss", $title, $summary, $content, $date, $topic_id);
        } else {
            die("Error preparing statement: " . $conn->error);
        }
    }

    // Execute the statement for creation
    if ($stmt && $stmt->execute()) {
        echo "Data inserted successfully.<br>"; // Debugging
        $_SESSION["create"] = "Post created successfully";
        header("Location: index.php");
        exit();
    } else {
        die("Error inserting data: " . $stmt->error);
    }

    // Close the statement
    if ($stmt) {
        $stmt->close();
    }
}

if (isset($_POST["update"])) {
    echo "Form submitted for update.<br>"; // Debugging

    // Sanitize inputs
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $summary = mysqli_real_escape_string($conn, $_POST["summary"]);
    $content = mysqli_real_escape_string($conn, $_POST["content"]);
    $date = mysqli_real_escape_string($conn, $_POST["date"]);
    $topic_id = isset($_POST["topic_id"]) ? mysqli_real_escape_string($conn, $_POST["topic_id"]) : null;
    $id = mysqli_real_escape_string($conn, $_POST["id"]);
    echo "Inputs sanitized.<br>"; // Debugging

    // Handle file upload
    $image = isset($_FILES["image"]["name"]) ? $_FILES["image"]["name"] : null;
    if ($image) {
        echo "File uploaded: $image<br>"; // Debugging
        $target_dir = "../sinan/uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($image);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "File moved to $target_file.<br>"; // Debugging
        } else {
            die("File upload failed!");
        }
    }

    // Prepare the SQL statement for update
    if ($image) {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, summary = ?, content = ?, date = ?, topic_id = ?, image = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("ssssssi", $title, $summary, $content, $date, $topic_id, $image, $id);
        } else {
            die("Error preparing statement: " . $conn->error);
        }
    } else {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, summary = ?, content = ?, date = ?, topic_id = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("sssssi", $title, $summary, $content, $date, $topic_id, $id);
        } else {
            die("Error preparing statement: " . $conn->error);
        }
    }

    // Execute the statement for update
    if ($stmt && $stmt->execute()) {
        echo "Data updated successfully.<br>"; // Debugging
        $_SESSION["update"] = "Post updated successfully";
        header("Location: index.php");
        exit();
    } else {
        die("Error updating data: " . $stmt->error);
    }

    // Close the statement
    if ($stmt) {
        $stmt->close();
    }
}
?>