<?php
include_once(__DIR__ . "/../config.php");
if (isset($_POST['high_brod'])) {
    $caster = mysqli_real_escape_string($conn, $_POST['high_brod']);
    if ($caster === 'All') {
        $sql = $conn->prepare("SELECT * FROM highlights");
        $sql->execute();
        $resp = $sql->get_result();
    } else {
        $sql = "SELECT * FROM highlights WHERE user_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $caster);
        $stmt->execute();
        $resp = $stmt->get_result();
    }
    $sort_by = 'ASC';
} else if (isset($_GET['column']) && isset($_GET['sort'])) {
    $column = $_GET['column'];
    $sort = $_GET['sort'];
    $allowed_columns = array('url', 'title', 'user_name', 'description', 'game_name', 'viewable', 'created_at', 'duration', 'added');
    $sort_by = $sort === 'DESC' ? 'DESC' : 'ASC';
    $selected_column = in_array($column, $allowed_columns) ? $column : "title";
    $sql = "SELECT * FROM highlights ORDER BY NATURAL_SORT_KEY({$selected_column}) {$sort_by}";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resp = $stmt->get_result();
    $sort_by = $sort === 'ASC' ? 'DESC' : 'ASC';
} else {
    $sql = $conn->prepare("SELECT * FROM highlights");
    $sql->execute();
    $resp = $sql->get_result();
    $sort_by = 'ASC';
}
$row_cnt = $resp->num_rows;
$resp2 = mysqli_query($conn, "SELECT updated FROM db_updated WHERE table_name='highlights' ORDER BY updated DESC LIMIT 1");
$resp2_row = mysqli_fetch_array($resp2);
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <title>Highlights | OnlyMans</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="description" content="Twitch highlights from OnlyMans users">
    <meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/highlights_styles.css" type="text/css">
    <link rel="alternate" type="application/rss+xml" title="OnlyMans site news" href="/rss.xml">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
</head>

<body>
    <?php echo file_get_contents("html/navigation.html"); ?>
    <div id="main-cont">
        <h3 align='center'>Highlights table</h3>
        <h3><?php echo "Number of results: " . $row_cnt; ?></h3>
        <h4><?php echo "Last updated: " . $resp2_row['updated']; ?></h4>
        <table class="highlight_table" border='2' align='center'>
            <thead>
                <tr>
                    <th><a href=<?php echo "/highlights.php?column=url&sort={$sort_by}"; ?>>URL</a></th>
                    <th><a href=<?php echo "/highlights.php?column=title&sort={$sort_by}"; ?>>Title</a></th>
                    <th><a href=<?php echo "/highlights.php?column=user_name&sort={$sort_by}"; ?>>Broadcaster</a></th>
                    <th><a href=<?php echo "/highlights.php?column=description&sort={$sort_by}"; ?>>Description</a></th>
                    <th><a href=<?php echo "/highlights.php?column=game_name&sort={$sort_by}"; ?>>Game</a></th>
                    <th><a href=<?php echo "/highlights.php?column=viewable&sort={$sort_by}"; ?>>Viewable</a></th>
                    <th><a href=<?php echo "/highlights.php?column=created_at&sort={$sort_by}"; ?>>Date</a></th>
                    <th><a href=<?php echo "/highlights.php?column=duration&sort={$sort_by}"; ?>>Duration</a></th>
                    <th><a href=<?php echo "/highlights.php?column=added&sort={$sort_by}"; ?>>Added to DB</a></th>
                </tr>
            </thead>
            <?php
            while ($fetch = mysqli_fetch_array($resp)) {
                echo "<tr>";
                echo "<td><a href=\"https://www.twitch.tv/videos/{$fetch['url']}\" target=\"_blank\">" . $fetch['url'] . "</a></td>";
                echo "<td><a href=\"highlight.php?url={$fetch['url']}\">" . $fetch['title'] . "</a></td>";
                echo "<td>" . $fetch['user_name'] . "</td>";
                echo "<td>" . $fetch['description'] . "</td>";
                echo "<td>" . $fetch['game_name'] . "</td>";
                echo "<td>" . $fetch['viewable'] . "</td>";
                echo "<td>" . $fetch['created_at'] . "</td>";
                echo "<td>" . $fetch['duration'] . "</td>";
                echo "<td>" . $fetch['added'] . "</td>";
                echo "</tr>";
            } ?>
        </table>
        <?php echo file_get_contents("html/footer.html"); ?>
