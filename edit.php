<?php
session_start();
include("config/database.php");

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST['filename'];
    $query = "UPDATE documents SET filename='$new_name' WHERE id='$id' AND user_id='$user_id'";
    if (mysqli_query($conn, $query)) {
        echo "Filename updated successfully.";
    } else {
        echo "Update failed.";
    }
}

$query = "SELECT * FROM documents WHERE id='$id' AND user_id='$user_id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "No such file.";
    exit;
}
?>

<h2>Edit Filename</h2>
<form method="post" action="">
    <label>Filename:</label>
    <input type="text" name="filename" value="<?php echo htmlspecialchars($row['filename']); ?>" required>
    <button type="submit">Save</button>
</form>
