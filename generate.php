<?php

exec('cd python && python3 generate_title.py', $python);
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
            <blockquote><?php echo $python[0]; ?></blockquote>
        </div>
    </div>

<?php echo file_get_contents("html/footer.html"); ?>
