<?php
if (isset($_FILES["file"])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile);

    // Call Python script
    $escapedPath = escapeshellarg($targetFile);
    $output = shell_exec("python3 ocr/ocr_analyze.py $escapedPath");

    // Save output to JSON
    $resultPath = $targetFile . ".json";
    file_put_contents($resultPath, $output);

    // Redirect to analysis.php
    header("Location: analysis.php?result=" . urlencode($resultPath));
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Health Report</title>
</head>
<body>
    <h2>Upload Health Report (PDF/Image)</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required />
        <button type="submit">Upload & Analyze</button>
    </form>
</body>
</html>
