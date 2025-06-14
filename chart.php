<?php
require 'db.php'; // Include your DB connection

// Query to get total order amount grouped by month
$sql = "
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') AS month, 
        SUM(total_amount) AS total 
    FROM orders 
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY DATE_FORMAT(created_at, '%Y-%m') ASC
";
$result = $conn->query($sql);

// Prepare data for the chart
$months = [];
$totals = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['month'];
        $totals[] = $row['total'];
    }
}

// Chart dimensions
$width = 800;
$height = 400;

// Create the image
$image = imagecreate($width, $height);

// Colors
$backgroundColor = imagecolorallocate($image, 240, 240, 240); // Light gray
$barColor = imagecolorallocate($image, 75, 192, 192); // Cyan
$textColor = imagecolorallocate($image, 0, 0, 0); // Black

// Define margins and scales
$margin = 50;
$barWidth = 40;
$barSpacing = 20;
$maxValue = max($totals);
$scale = ($height - 2 * $margin) / $maxValue;

// Draw axis lines
imageline($image, $margin, $height - $margin, $width - $margin, $height - $margin, $textColor); // X-axis
imageline($image, $margin, $margin, $margin, $height - $margin, $textColor); // Y-axis

// Draw bars
for ($i = 0; $i < count($totals); $i++) {
    $x1 = $margin + $i * ($barWidth + $barSpacing);
    $y1 = $height - $margin - ($totals[$i] * $scale);
    $x2 = $x1 + $barWidth;
    $y2 = $height - $margin;

    // Draw bar
    imagefilledrectangle($image, $x1, $y1, $x2, $y2, $barColor);

    // Add month labels
    imagestring($image, 3, $x1 + 5, $height - $margin + 5, $months[$i], $textColor);

    // Add value labels
    imagestring($image, 3, $x1 + 5, $y1 - 15, $totals[$i], $textColor);
}

// Output the image
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>
