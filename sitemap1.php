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
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
     <url>
         <loc>https://onlymans.site/index.php</loc>
         <lastmod>2024-04-27</lastmod>
         <priority>0.5</priority>
     </url>
     <url>
         <loc>https://onlymans.site/clips.php</loc>
         <lastmod><?php echo $clip_mod ?></lastmod>
         <changefreq>daily</changefreq>
         <priority>1</priority>
     </url>
     <url>
         <loc>https://onlymans.site/highlights.php</loc>
         <lastmod><?php echo $highlight_mod ?></lastmod>
         <changefreq>daily</changefreq>
         <priority>1</priority>
     </url>
     <url>
         <loc>https://onlymans.site/generate.php</loc>
         <lastmod>2024-04-27</lastmod>
         <priority>0.5</priority>
     </url>
     <url>
         <loc>https://onlymans.site/random_steamgame.php</loc>
         <lastmod>2024-11-09</lastmod>
         <priority>0.5</priority>
     </url>
     <url>
         <loc>https://onlymans.site/search.php</loc>
         <lastmod>2024-04-27</lastmod>
         <priority>0.5</priority>
     </url>
     <url>
         <loc>https://onlymans.site/statistics.php</loc>
         <lastmod>2024-04-27</lastmod>
         <priority>0.5</priority>
     </url>
     <url>
         <loc>https://onlymans.site/register.php</loc>
         <lastmod>2024-07-21</lastmod>
         <priority>0.5</priority>
     </url>
     <url>
         <loc>https://onlymans.site/login.php</loc>
         <lastmod>2024-07-21</lastmod>
         <priority>0.5</priority>
     </url>
     <url>
         <loc>https://onlymans.site/user/dashboard.php</loc>
         <lastmod>2024-07-21</lastmod>
         <priority>0.5</priority>
     </url>
</urlset>
