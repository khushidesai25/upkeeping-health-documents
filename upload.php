<?php
session_start();
include("config/database.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $filename = $_FILES["document"]["name"];
    $tempname = $_FILES["document"]["tmp_name"];
    $folder = "uploads/" . $filename;

    if (move_uploaded_file($tempname, $folder)) {
        $query = "INSERT INTO documents (user_id, filename) VALUES ('$user_id', '$filename')";
        mysqli_query($conn, $query);
        echo "Document uploaded successfully!";
    } else {
        echo "Failed to upload document.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Document</title>
</head>
<body>
    <h2>Upload Health Document</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="document" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
