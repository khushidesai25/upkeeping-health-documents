<?php
session_start();
include("config/database.php");

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
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
        unlink($file_path);
    }

    $delete_query = "DELETE FROM documents WHERE id='$id' AND user_id='$user_id'";
    mysqli_query($conn, $delete_query);
}

header("Location: dashboard.php");
exit;
?>
