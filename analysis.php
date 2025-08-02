<?php
if (!isset($_GET["result"])) {
    echo "No result file provided.";
    exit();
}

$resultPath = $_GET["result"];
if (!file_exists($resultPath)) {
    echo "Result file not found.";
    exit();
}

$resultData = json_decode(file_get_contents($resultPath), true);
$metrics = $resultData["metrics"];
$prediction = $resultData["prediction"];
$text = $resultData["text"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Health Report Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Health Risk Prediction: 
        <span style="color: <?= $prediction === 'High' ? 'red' : ($prediction === 'Moderate' ? 'orange' : 'green') ?>">
            <?= $prediction ?>
        </span>
    </h2>

    <h3>Extracted Metrics</h3>
    <ul>
        <?php foreach ($metrics as $key => $value): ?>
            <li><strong><?= ucfirst($key) ?>:</strong> <?= $value ?></li>
        <?php endforeach; ?>
    </ul>

    <h3>Report Text</h3>
    <pre><?= htmlspecialchars($text) ?></pre>

    <h3>Visual Analysis</h3>
    <canvas id="chart" width="400" height="200"></canvas>
    <script>
        const ctx = document.getElementById('chart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($metrics)) ?>,
                datasets: [{
                    label: 'Health Indicators',
                    data: <?= json_encode(array_values($metrics)) ?>,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)'
                }]
            }
        });
    </script>
</body>
</html>
