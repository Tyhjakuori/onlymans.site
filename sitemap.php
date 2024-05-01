<?php
header("Content-type:text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
include_once(__DIR__ . "/../config.php");
$resp1 = mysqli_query($conn, "SELECT updated FROM db_updated WHERE table_name='clips' ORDER BY updated DESC LIMIT 1");
$resp1_row = mysqli_fetch_array($resp1);
$resp2 = mysqli_query($conn, "SELECT updated FROM db_updated WHERE table_name='highlights' ORDER BY updated DESC LIMIT 1");
$resp2_row = mysqli_fetch_array($resp2);
$clip_mod = date("Y-m-d", strtotime($resp1_row[0]));
$highlight_mod = date("Y-m-d", strtotime($resp2_row[0]));
?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>https://onlymans.site/sitemap1.xml</loc>
        <lastmod>2024-04-27</lastmod>
    </sitemap>
    <sitemap>
        <loc>https://onlymans.site/sitemap2.xml</loc>
        <lastmod><?php echo $clip_mod ?></lastmod>
    </sitemap>
    <sitemap>
        <loc>https://onlymans.site/sitemap3.xml</loc>
        <lastmod><?php echo $highlight_mod ?></lastmod>
    </sitemap>
</sitemapindex>
