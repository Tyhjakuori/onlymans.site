<?php
include_once(__DIR__ . "/../../config.php");
include_once("../auth.php");
$username = mysqli_real_escape_string($conn, $_SESSION['username']);
$sql = "SELECT * FROM playlists WHERE creator = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$resp = $stmt->get_results;
$row_count = $resp->num_rows;
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="UTF-8">
    <title>Playlists | OnlyMans</title>
    <meta name="description" contents="Site for OnlyMans">
    <link rel="icon" href="/public/favicon.svg">
    <link rel="stylesheet" href="/css/user/dashboard_styles.css" type="text/css">
</head>

<body>
    <header class="nav">
        <nav class="navigation">
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../clips.php">Clips</a></li>
                <li><a href="../highlights.php">Highlights</a></li>
                <li><a href="../generate.php">Generate title</a></li>
                <li><a href="../logout.php">Log out</a></li>
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
    <div class="main-cont">
        <p><?php echo "Number of playlists created: " . $row_cnt; ?></p>
        <div class="playlists">
            <?php
                while ($fetch = mysqli_fetch_array($resp)) {
                    echo "<tr>";
                    echo "<td><a href=\"/playlist.php?playlistid={$fetch['playlistid']}\" target=\"_blank\">" . $fetch['name'] . "</a></td>";
                    echo "<td>" . $fetch['number_entries'] . "</td>";
                    echo "<td>" . $fetch['type'] . "</td>";
                    echo "<td>" . $fetch['total_length'] . "</td>";
                    echo "<td>" . $fetch['created_at'] . "</td>";
                    echo "<td>" . $fetch['updated_at'] . "</td>";
                    echo "</tr>";
                } ?>
        </div>
        <p><a href="create_clip_playlist.php">Create new clip playlist</a></p>
        <p><a href="create_highlight_playlist.php">Create new highlight playlist</a></p>

        <?php echo file_get_contents("../html/footer.html"); ?>
