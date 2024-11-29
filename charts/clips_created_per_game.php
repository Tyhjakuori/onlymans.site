<?php
header('Content-type: image/png');
include_once(__DIR__ . "/../../config.php");

// Get amount of clips per game
$dates = $conn->query("SELECT game_name, COUNT(*) c FROM clips GROUP BY game_name HAVING c > 1 ORDER BY c DESC LIMIT 70");

/*
 * Chart data
 */

$data = array();
while ($row = mysqli_fetch_assoc($dates)) {
    $data[$row["game_name"]] = $row["c"];
}

/*
 * Chart settings and create image
 */

// Image dimensions
$imageWidth = 1280;
$imageHeight = 720;

// Grid dimensions and placement within image
$gridTop = 10;
$gridLeft = 50;
$gridBottom = 620;
$gridRight = 1230;
$gridHeight = $gridBottom - $gridTop;
$gridWidth = $gridRight - $gridLeft;

// Bar and line width
$lineWidth = 1;
$barWidth = 10;

// Font settings
$font = __DIR__ . '/LiberationSerif-Regular.ttf';
$fontSize = 12;

// Margin between label and axis
$labelMargin = 8;

// Max value on y-axis
$yMaxValue = 560;

// Distance between grid lines on y-axis
$yLabelSpan = 20;

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
    imageline($chart, $gridLeft, round($y), $gridRight, round($y), $gridColor);

    // draw right aligned label
    $labelBox = imagettfbbox($fontSize, 0, $font, strval($i));
    $labelWidth = $labelBox[4] - $labelBox[0];

    $labelX = $gridLeft - $labelWidth - $labelMargin;
    $labelY = $y + $fontSize / 2;

	imagettftext($chart, $fontSize, 0, $labelX, round($labelY), $labelColor, $font, strval($i));
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

    imagefilledrectangle($chart,round($x1), round($y1), round($x2), $y2, $barColor);

    // Draw the label
    $labelBox = imagettfbbox($fontSize, 0, $font, $key);
    $labelWidth = $labelBox[7] - $labelBox[0];

    $labelX = $itemX - $labelWidth / 2;
    $labelY = $gridBottom + $labelMargin + $fontSize + 75;

    imagettftext($chart, $fontSize, 90, round($labelX), $labelY, $labelColor, $font, $key);

    $itemX += $barSpacing;
}

/*
 * Output image to browser
 */
imagepng($chart);
imagedestroy($chart);
