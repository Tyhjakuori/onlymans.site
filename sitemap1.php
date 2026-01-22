<?php
header("Content-type:text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
include_once(__DIR__ . "/../config.php");
$resp1 = mysqli_query($conn, "SELECT updated FROM db_updated WHERE table_name='clips' ORDER BY updated DESC LIMIT 1");
$resp1_row = mysqli_fetch_array($resp1);
$clip_mod = date("Y-m-d", strtotime($resp1_row[0]));
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
     <url>
         <loc>https://katabasis.onlymans.site/index.php</loc>
         <lastmod>2026-01-22</lastmod>
         <priority>0.5</priority>
     </url>
     <url>
         <loc>https://katabasis.onlymans.site/clips.php</loc>
         <lastmod><?php echo $clip_mod ?></lastmod>
         <changefreq>daily</changefreq>
         <priority>1</priority>
     </url>
     <url>
         <loc>https://katabasis.onlymans.site/statistics.php</loc>
         <lastmod>2026-01-22</lastmod>
         <priority>0.5</priority>
     </url>
</urlset>
