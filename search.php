<?php
include_once(__DIR__ . "/../config.php");
$resp = null;
$resp_high = null;
if (isset($_POST['uparameters'])) {
    $params = $_POST['uparameters'];
    $columns = $_POST['columns'];
    $allowed = ["name", "broadcaster", "creator_name", "game_id", "game_name", "title"];
    if (in_array($columns, $allowed)) {
        $column = $columns;
    } else {
        $column = "title";
    }
    $sql = "SELECT * FROM clips WHERE $column LIKE ?";
    $stmt = $conn->prepare($sql);
    $new_param = strtolower("%$params%");
    $stmt->bind_param("s", $new_param);
    $stmt->execute();
    $resp = $stmt->get_result();
    $row_cnt = $resp->num_rows;
} else if (isset($_POST['highparams'])) {
    $high_params = $_POST['highparams'];
    $columns = $_POST['columns'];
    $allowed = ["title", "url", "user_name", "description", "game_name"];
    if (in_array($columns, $allowed)) {
        $column = $columns;
    } else {
        $column = "title";
    }
    $sql = "SELECT * FROM highlights WHERE $column LIKE ?";
    $stmt = $conn->prepare($sql);
    $new_param = strtolower("%$high_params%");
    $stmt->bind_param("s", $new_param);
    $stmt->execute();
    $resp_high = $stmt->get_result();
    $row_cnt = $resp_high->num_rows;
}
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <title>Search | OnlyMans</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="description" content="Search clips and highlights from OnlyMans users content">
    <meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/search_styles.css" type="text/css">
    <link rel="alternate" type="application/rss+xml" title="OnlyMans site news" href="/rss.xml">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
</head>

<body>
    <div class="main-cont">
        <header class="nav">
            <nav class="navigation">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="clips.php">Clips</a></li>
                    <li><a href="highlights.php">Highlights</a></li>
                    <li><a href="generate.php">Generate title</a></li>
                    <div class="search">
                        <li>
                            <form action='search.php' method='POST'>
                                <select name="columns">
                                    <option value="name">Clip ID</option>
                                    <option value="title">Clip title</option>
                                    <option value="broadcaster">Broadcaster</option>
                                    <option value="creator_name">Clipper</option>
                                    <option value="game_name">Game name</option>
                                    <option value="game_id">Game ID</option>
                                </select>
                                <input type="text" name="uparameters" placeholder="Search clips...">
                                <input type="submit" value="search clips">
                            </form>
                        </li>
                        <li>
                            <form action='search.php' method='POST'>
                                <select name="columns">
                                    <option value="title">Title</option>
                                    <option value="url">Highlight url</option>
                                    <option value="user_name">Broadcaster</option>
                                    <option value="description">Description</option>
                                    <option value="game_name">Game name</option>
                                </select>
                                <input type="text" name="highparams" placeholder="Search highlights...">
                                <input type="submit" value="search highlights">
                            </form>
                        </li>
                    </div>
                </ul>
            </nav>
        </header>
        <?php if ($resp_high !== null) : ?>
            <table class="highlight_table" border='2' align='center'>
            <?php else : ?>
                <table class="clip_table" border='2' align='center'>
                <?php endif; ?>
                <h3 align='center'>Search results</h3>
                <h3><?php echo "Number of results: " . $row_cnt; ?></h3>
                <thead>
                    <tr>
                        <?php if ($resp_high !== null) : ?>
                            <th>URL</th>
                            <th>Title</th>
                            <th>Broadcaster</th>
                            <th>Description</th>
                            <th>Game</th>
                            <th>Viewable</th>
                            <th>Date</th>
                            <th>Duration</th>
                            <th>Added to DB</th>
                        <?php else : ?>
                            <th>Clip id</th>
                            <th>Clip title</th>
                            <th>Broadcaster</th>
                            <th>Clipper</th>
                            <th>Game</th>
                            <th>View count</th>
                            <th>Duration</th>
                            <th>Date</th>
                            <th>Added to DB</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resp_high) {
                        while ($fetch = mysqli_fetch_array($resp_high)) {
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
                        }
                    } else {
                        while ($fetch = mysqli_fetch_array($resp)) {
                            echo "<tr>";
                            echo "<td><a href=\"https://clips.twitch.tv/{$fetch['name']}\" target=\"_blank\">" . $fetch['name'] . "</a></td>";
                            echo "<td><a href='clip.php?clipid={$fetch['name']}'>" . $fetch['title'] . "</a></td>";
                            echo "<td>" . $fetch['broadcaster'] . "</td>";
                            echo "<td>" . $fetch['creator_name'] . "</td>";
                            if ($fetch['game_name'] === NULL) {
                                echo "<td>" . $fetch['game_id'] . "</td>";
                            } else {
                                echo "<td>" . $fetch['game_name'] . "</td>";
                            }
                            echo "<td>" . $fetch['view_count'] . "</td>";
                            echo "<td>" . $fetch['duration'] . "</td>";
                            echo "<td>" . $fetch['created_at'] . "</td>";
                            echo "<td>" . $fetch['added'] . "</td>";
                            echo "</tr>";
                        }
                    } ?>
                </tbody>
                </table>
                <?php echo file_get_contents("html/footer.html"); ?>