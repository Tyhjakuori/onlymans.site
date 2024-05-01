<?php

include_once(__DIR__ . "/../../config.php");

// Get clips amounts by year and month
$dates = $conn->query("SELECT DATE_FORMAT(created_at, '%Y'), COUNT(1) as clips_created FROM clips GROUP BY DATE_FORMAT(created_at, '%Y')");

/*
 * Chart data
 */

$data = array();
// Add chart data to main array
while ($row = mysqli_fetch_assoc($dates)) {
    $data[$row["DATE_FORMAT(created_at, '%Y')"]] = $row["clips_created"];
}

/*
 * Chart settings and create image
 */

// Image dimensions
$imageWidth = 1280;
$imageHeight = 720;

// Grid dimensions and placement within image
$gridTop = 20;
$gridLeft = 40;
$gridBottom = 660;
$gridRight = 1230;
$gridHeight = $gridBottom - $gridTop;
$gridWidth = $gridRight - $gridLeft;

// Bar and line width
$lineWidth = 2;
$barWidth = 15;

// Font settings
$font = __DIR__ . '/LiberationSerif-Regular.ttf';
$fontSize = 13;

// Margin between label and axis
$labelMargin = 4;

// Max value on y-axis
$yMaxValue = 1500;

// Distance between grid lines on y-axis
$yLabelSpan = 50;

// Init image
$chart = imagecreate($imageWidth, $imageHeight);

// Setup colors
$backgroundColor = imagecolorallocate($chart, 255, 255, 255);
$axisColor = imagecolorallocate($chart, 85, 85, 85);
$labelColor = $axisColor;
$gridColor = imagecolorallocate($chart, 212, 212, 212);
$barColor = imagecolorallocate($chart, 47, 133, 217);

imagefill($chart, 0, 0, $backgroundColor);

imagesetthickness($chart, $lineWidth);

/*
 * Print grid lines bottom up
 */

for ($i = 0; $i <= $yMaxValue; $i += $yLabelSpan) {
    $y = $gridBottom - $i * $gridHeight / $yMaxValue;

    // draw the line
    imageline($chart, $gridLeft, $y, $gridRight, $y, $gridColor);

    // draw right aligned label
    $labelBox = imagettfbbox($fontSize, 0, $font, strval($i));
    $labelWidth = $labelBox[4] - $labelBox[0];

    $labelX = $gridLeft - $labelWidth - $labelMargin;
    $labelY = $y + $fontSize / 2;

    imagettftext($chart, $fontSize, 0, $labelX, $labelY, $labelColor, $font, strval($i));
}

/*
 * Draw x- and y-axis
 */

imageline($chart, $gridLeft, $gridTop, $gridLeft, $gridBottom, $axisColor);
imageline($chart, $gridLeft, $gridBottom, $gridRight, $gridBottom, $axisColor);

/*
 * Draw the bars with labels
 */
$barSpacing = $gridWidth / count($data);
$itemX = $gridLeft + $barSpacing / 2;

foreach ($data as $key => $value) {
    // Draw the bar
    $x1 = $itemX - $barWidth / 2;
    $y1 = $gridBottom - $value / $yMaxValue * $gridHeight;
    $x2 = $itemX + $barWidth / 2;
    $y2 = $gridBottom - 1;

    imagefilledrectangle($chart, $x1, $y1, $x2, $y2, $barColor);

    // Draw the label
    $labelBox = imagettfbbox($fontSize, 0, $font, $key);
    $labelWidth = $labelBox[7] - $labelBox[0];

    $labelX = $itemX - $labelWidth / 2;
    $labelY = $gridBottom + $labelMargin + $fontSize + 38;

    imagettftext($chart, $fontSize, 0, $labelX-20, $labelY-20, $labelColor, $font, $key);

    $itemX += $barSpacing;
}

/*
 * Output image to browser
 */

header('Content-Type: image/png');
imagepng($chart);

