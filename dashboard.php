<?php
session_start();
include("config/database.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM documents WHERE user_id='$user_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Dashboard</title>
</head>
<body>
    <h2>Welcome to your dashboard</h2>
    <p><a href="upload.php">Upload New Document</a> | <a href="logout.php">Logout</a></p>

    <h3>Your Uploaded Documents:</h3>
    <ul>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <li>
                <?php echo htmlspecialchars($row['filename']); ?>
                - <a href="view.php?id=<?php echo $row['id']; ?>">View</a>
                - <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                - <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this file?')">Delete</a>
            </li>
        <?php } ?>
    </ul>
</body>
</html>
