<?php
header("Content-type:text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
include_once(__DIR__ . "/../config.php");
$resp1 = $conn->query("SELECT * FROM clips");
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php
    while ($fetch = $resp1->fetch_array()) {
        $clip_mod = date("Y-m-d", strtotime($fetch['edited']));
        echo "<url>";
        echo "<loc>https://onlymans.site/clip.php?clipid={$fetch['name']}</loc>";
        echo "<lastmod>{$clip_mod}</lastmod>";
        echo "<priority>0.5</priority>";
        echo "</url>";
    }
    ?>
</urlset>