<?php
include_once(__DIR__ . "/../config.php");
if (isset($_POST['clip_brod'])) {
    $caster = mysqli_real_escape_string($conn, $_POST['clip_brod']);
    if ($caster === "All") {
        $sql = $conn->prepare("SELECT * FROM clips");
        $sql->execute();
        $resp = $sql->get_result();
    } else {
        $sql = "SELECT * FROM clips WHERE broadcaster = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $caster);
        $stmt->execute();
        $resp = $stmt->get_result();
    }
} else {
    $sql = $conn->prepare("SELECT * FROM clips");
    $sql->execute();
    $resp = $sql->get_result();
}
$row_cnt = $resp->num_rows;
$resp2 = mysqli_query($conn, "SELECT updated FROM db_updated WHERE table_name='clips' ORDER BY updated DESC LIMIT 1");
$resp2_row = mysqli_fetch_array($resp2);
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="UTF-8">
    <title>Clips | OnlyMans</title>
    <meta name="description" contents="Site for OnlyMans">
    <meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1" />
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/clips_styles.css" type="text/css">
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
        <table class="clip_table" border='2' align='center'>
            <h3 align='center'>Clips table</h3>
            <h3><?php echo "Number of results: " . $row_cnt; ?></h3>
            <h4><?php echo "Last updated: " . $resp2_row['updated']; ?></h4>
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
                } ?>
            </tbody>
        </table>
        <script src="js/jQuery.js" type="text/javascript"></script>
        <script src="js/sorter.js" type="text/javascript"></script>
    </div>
<?php echo file_get_contents("html/footer.html"); ?>
