<?php
function generate_title()
{
    $lines = array();
    $file = "./titles/stream_titles.txt";
    $handle = @fopen($file, "r") or die();
    if ($handle) {
        while (($buffer = fgets($handle, 4096)) !== false) {
            $words = explode(" ", $buffer);
            foreach ($words as $word) {
                if (!in_array($word, $lines)) {
                    $lines[] = $word;
                }
            }
        }
        if (!feof($handle)) {
            error_log("Error: unexpected fgets() fail");
        }
        fclose($handle);
    }
    $random_integer = random_int(1, 6);
    shuffle($lines);
    $sentence = array_rand($lines, $random_integer);
    $sent = null;
    if ($random_integer === 1) {
        $sent = $lines[0];
    } else {
        $counted = count($sentence);
        for ($i = 0; $i < $counted; $i++) {
            $index = $sentence[$i];
            $sent .= rtrim($lines[$index]) . " ";
        }
    }
    return rtrim($sent);
}
$result = generate_title();
$page = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <title>Generate title | OnlyMans</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="description" content="Generate a title in OnlyMans fashion">
    <meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/generate_styles.css" type="text/css">
    <link rel="alternate" type="application/rss+xml" title="OnlyMans site news" href="/rss.xml">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
</head>

<body>
    <div class="main-cont">
        <?php echo file_get_contents("html/navigation.html"); ?>
        <br>
        <div class="result">
            <h2 class="title2">Generate a stream title!</h2>
            <a href="./generate.php">Reload to try again</a>
            <blockquote><?php echo $result; ?></blockquote>
        </div>
    </div>

    <?php echo file_get_contents("html/footer.html"); ?>
