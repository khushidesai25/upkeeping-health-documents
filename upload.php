<?php
// Start the session and include any necessary files
session_start();
include('config/database.php'); // if database access is required later

// Define the upload folder
$uploadDir = 'uploads/reports/'; // Ensure this folder exists and is writable

if (isset($_POST['submit'])) {
    // Check if file was uploaded without errors
    if (isset($_FILES['health_report']) && $_FILES['health_report']['error'] === 0) {
        // Allowed file extensions (images and PDFs)
        $allowed = array("jpg" => "image/jpeg", "jpeg" => "image/jpeg", 
                         "png" => "image/png", "pdf" => "application/pdf");

        $filename = $_FILES['health_report']['name'];
        $filetype = $_FILES['health_report']['type'];
        $filesize = $_FILES['health_report']['size'];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists(strtolower($ext), $allowed)) {
            die("Error: Please select a valid file format.");
        }

        // Verify file size - limit to 5MB for example.
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) {
            die("Error: File size is larger than the allowed limit.");
        }

        // Verify MIME type of the file
        if (in_array($filetype, $allowed)) {
            // Create a unique file name and define the target file path
            $newFileName = uniqid("report_", true) . "." . $ext;
            $targetFile = $uploadDir . $newFileName;

            // Move the file to the designated folder
            if (move_uploaded_file($_FILES["health_report"]["tmp_name"], $targetFile)) {
                echo "File uploaded successfully.<br>";

                // Call the Python script to perform OCR and ML analysis
                // Adjust the path to your Python script accordingly
                $pythonScript = __DIR__ . '/ocr/ocr_analyze.py';
                
                // Make sure to properly escape the file path
                $command = escapeshellcmd("python3 " . $pythonScript . " " . escapeshellarg($targetFile));
                $output = shell_exec($command);
                
                if ($output) {
                    // Decode JSON output from the Python script
                    $analysisData = json_decode($output, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        echo "<h3>Analysis Result:</h3>";
                        // Display analysis results (customize based on your Python output structure)
                        if (isset($analysisData['text'])) {
                            echo "<strong>Extracted Report Text:</strong><br>" . nl2br(htmlspecialchars($analysisData['text'])) . "<br><br>";
                        }
                        if (isset($analysisData['predictions'])) {
                            echo "<strong>Health Predictions:</strong><br>";
                            foreach ($analysisData['predictions'] as $condition => $result) {
                                echo htmlspecialchars($condition) . ": " . htmlspecialchars($result) . "<br>";
                            }
                        }
                    } else {
                        echo "Error decoding the analysis result.";
                    }
                } else {
                    echo "No output from the analysis script.";
                }
            } else {
                echo "Error: There was a problem uploading your file.";
            }
        } else {
            echo "Error: There was a problem with your file type.";
        }
    } else {
        echo "Error: " . $_FILES['health_report']['error'];
    }
} else {
    // If no file has been submitted, display the upload form.
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Upload Health Report</title>
        <link rel="stylesheet" type="text/css" href="assests/css/style.css">
    </head>
    <body>
        <h2>Upload Your Health Report</h2>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="health_report">Select file (PDF, JPG, PNG):</label>
            <input type="file" name="health_report" id="health_report" required>
            <br><br>
            <input type="submit" name="submit" value="Upload Report">
        </form>
    </body>
    </html>
    <?php
}
?>
