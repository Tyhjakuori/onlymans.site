<?php
header("Content-type: application/rss+xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
include_once(__DIR__ . "/../config.php");
$feed = $conn->query("SELECT * FROM rss_feed ORDER BY pubdate DESC");
$feed_build = $conn->query("SELECT updated FROM db_updated WHERE table_name='rss_feed' ORDER BY updated DESC LIMIT 1");
$build_row = mysqli_fetch_array($feed_build);
$build_date = new DateTime($build_row['updated']);
echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
echo "<channel>";
echo "<title>OnlyMans</title>";
echo "<link>https://onlymans.site/rss.xml</link>";
echo '<atom:link href="https://onlymans.site/rss.xml" rel="self" type="application/rss+xml" />';
echo "<description>OnlyMans what's new rss feed</description>";
echo "<language>en-us</language>";
echo '<lastBuildDate>' . date_format($build_date, 'D, d M Y H:i:s') . ' +0300</lastBuildDate>';
while ($row = mysqli_fetch_array($feed)) {
    $date = new DateTime($row['pubdate']);
    echo "<item>";
    echo "<title>" . $row['title'] . "</title>";
    echo "<description>" . $row['description'] . "</description>";
    echo "<pubDate>" . date_format($date, 'D, d M Y H:i:s') . " +0300</pubDate>";
    echo "<link>" . $row['link'] . "</link>";
    echo '<guid isPermaLink="true">' . $row['link'] . "</guid>";
    echo "</item>";
}
echo "</channel>";
echo "</rss>";

