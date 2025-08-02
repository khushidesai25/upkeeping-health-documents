<?php
session_start();
include("config/database.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM documents WHERE id='$id' AND user_id='$user_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $file_path = "uploads/" . $row['filename'];
    if (file_exists($file_path)) {
        echo "<h2>Viewing Document: " . htmlspecialchars($row['filename']) . "</h2>";
        echo "<embed src='$file_path' type='application/pdf' width='100%' height='600px'>";
    } else {
        echo "File not found.";
    }
} else {
    echo "No document found.";
}
?>
