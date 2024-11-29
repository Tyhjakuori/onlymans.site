<?php
include_once(__DIR__ . "/../../config.php");
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
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <title>Highlights | OnlyMans</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="description" content="Twitch highlights from OnlyMans users">
    <meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1">
    <link rel="icon" href="../public/favicon.svg">
    <link rel="stylesheet" href="../css/highlights_styles.css" type="text/css">
    <link rel="alternate" type="application/rss+xml" title="OnlyMans site news" href="/rss.xml">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
</head>

<body>
    <?php echo file_get_contents("../html/navigation.html"); ?>
    <div id="main-cont">
        <h3 align='center'>Create a highlight playlist</h3>
        <h3><?php echo "Number of highlights: " . $row_cnt; ?></h3>
        <p>Select highlights to add to the playlist by clicking the checkbox inside "URL" column</p>
        <p>Write a name for the playlist (will be used for the url so try to pick not THAT long one). Has to be unique</p>
        <form method="POST" action="create_playlist.php">
            <label for="playlist_name">Name for the playlist</label>
            <input type="text" name="playlist_name" required /><span> * required</span><br />
            <label for="description">Description of playlist</label>
            <input type="" name="description" /><span> optional</span><br />
            <input type="submit" value="Submit" />
            <table class="highlight_table" border='2' align='center'>
                <thead>
                    <tr>
                        <th><a href=<?php echo "/user/create_highlight_playlist.php?column=url&sort={$sort_by}"; ?>>URL</a></th>
                        <th><a href=<?php echo "/user/create_highlight_playlist.php?column=title&sort={$sort_by}"; ?>>Title</a></th>
                        <th><a href=<?php echo "/user/create_highlight_playlist.php?column=user_name&sort={$sort_by}"; ?>>Broadcaster</a></th>
                        <th><a href=<?php echo "/user/create_highlight_playlist.php?column=description&sort={$sort_by}"; ?>>Description</a></th>
                        <th><a href=<?php echo "/user/create_highlight_playlist.php?column=game_name&sort={$sort_by}"; ?>>Game</a></th>
                        <th><a href=<?php echo "/user/create_highlight_playlist.php?column=viewable&sort={$sort_by}"; ?>>Viewable</a></th>
                        <th><a href=<?php echo "/user/create_highlight_playlist.php?column=created_at&sort={$sort_by}"; ?>>Date</a></th>
                        <th><a href=<?php echo "/user/create_highlight_playlist.php?column=duration&sort={$sort_by}"; ?>>Duration</a></th>
                        <th><a href=<?php echo "/user/create_highlight_playlist.php?column=added&sort={$sort_by}"; ?>>Added to DB</a></th>
                    </tr>
                </thead>
                <?php
                while ($fetch = mysqli_fetch_array($resp)) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='highlight_ids[]' value=" . $fetch['url'] . ">" . $fetch['url'] . "</td>";
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
        </form>
        <?php echo file_get_contents("../html/footer.html"); ?>
