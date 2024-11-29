<?php
include_once(__DIR__ . "/../config.php");
$random_game = $conn->query("SELECT app_id FROM steam_appids ORDER BY RAND() LIMIT 1");
$row = mysqli_fetch_array($random_game);
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <title>Random steam game | OnlyMans</title>
	<meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1">
    <meta name="description" content="Looking for something new to play? Try your luck here">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/embed_steam.css" type="text/css">
    <link rel="alternate" type="application/rss+xml" title="OnlyMans site news" href="/rss.xml">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
</head>

<body>
    <div id="main-cont">
        <?php echo file_get_contents("html/navigation.html"); ?>
        <div class="steam_app_container">
            <div class="texts">
            <h2 class="title1">Random Steam game</h2>
            <a href="/random_steamgame.php">Reload to try again</a>
            </div>
            <div class="steam_container">
                <div id="contenedor">
                    <iframe src="https://store.steampowered.com/widget/<?php echo $row['app_id'] ?>" frameborder="0" width="1080" height="720"></iframe>
                </div>
            </div>
        </div>
        <?php echo file_get_contents("html/footer.html"); ?>
