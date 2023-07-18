<?php
include_once(__DIR__ . "/../config.php");
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
}
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="UTF-8">
    <title>Search | OnlyMans</title>
    <meta name="description" contents="Site for OnlyMans">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/search_styles.css" type="text/css">
</head>

<body>
    <div class="main-cont">
        <header class="nav">
            <nav class="navigation">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="clips.php">Clips</a></li>
                    <li><a href="highlights.php">Highlights</a></li>
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
                            <input type="text" name="uparameters" placeholder="Search...">
                            <input type="submit" value="search">
                        </form>
                    </li>
                </ul>
            </nav>
        </header>
        <table class="clip_table" border='2' align='center'>
            <h3 align='center'>Search results</h3>
            <h3><?php echo "Number of results: " . $row_cnt; ?></h3>
            <thead>
                <tr>
                    <th>Clip id</th>
                    <th>Clip title</th>
                    <th>Broadcaster</th>
                    <th>Clipper</th>
                    <th>Game</th>
                    <th>View count</th>
                    <th>Duration</th>
                    <th>Date</th>
                    <th>Added to DB</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($fetch = mysqli_fetch_array($resp)) {
                    echo "<tr>";
                    echo "<td><a href=\"https://clips.twitch.tv/{$fetch['name']}\" target=\"_blank\">" . $fetch['name'] . "</a></td>";
                    echo "<td><a href='clip.php?id={$fetch['id']}'>" . $fetch['title'] . "</a></td>";
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
                } ?>
            </tbody>
        </table>
</body>
<footer class="footer">
    Contact me at tyhjakuori@proton.me
</footer>

</html>