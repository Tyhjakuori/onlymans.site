<?php
if (isset($_GET['id'])) {
    include_once(__DIR__ . "/../config.php");
    $id = $_GET['id'];
    $sani_id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    if (filter_var($sani_id, FILTER_VALIDATE_INT) === 0 || !filter_var($sani_id, FILTER_VALIDATE_INT) === false) {
        $sql = "SELECT * FROM clips WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();
    } else {
        header('Location: clips.php');
        die();
    }
} elseif (isset($_GET['clipid'])) {
    include_once(__DIR__ . "/../config.php");
    $clip_id = $_GET['clipid'];
    $sani_clip = filter_var($clip_id, FILTER_SANITIZE_STRING);
    if (!preg_match('/[^a-z_\-0-9]/i', $sani_clip)) {
        $sql = "SELECT * FROM clips WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $clip_id);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();
    } else {
        header('Location: clips.php');
        die();
    }
} else {
    header('Location: clips.php');
    die();
}
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="UTF-8">
    <title><?php echo $row['title'] . " | OnlyMans" ?></title>
    <meta name="description" contents="Site for OnlyMans">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/clip_styles.css" type="text/css">
</head>

<body>
    <div id="main-cont">
        <header class="nav">
            <nav class="navigation">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="clips.php">Clips</a></li>
                    <li><a href="highlights.php">Highlights</a></li>
                    <li><a href="generate.php">Generate title</a></li>
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
        <div class="clipInfo">
            <h1><?php echo "Clip title: " . $row['title'] ?></h1><br />
            <div class="boxDiv">
                <div class="clipDiv">
                    <div class="floatDiv">
                        <p><?php echo "<b>Broadcaster: </b><a href='https://www.twitch.tv/" . $row['broadcaster'] . "' target='_blank'>" . $row['broadcaster'] . "</a>" ?></p>
                    </div>
                    <div class="floatDiv">
                        <p><?php echo "<b>Clip creator: </b>" . $row['creator_name'] ?></p>
                    </div>
                    <div class="floatDiv">
                        <p><?php echo "<b>Game: </b>" . ($row['game_name'] != NULL ? $row['game_name'] : $row['game_id']) ?></p>
                    </div>
                    <div class="floatDiv">
                        <p><?php echo "<b>Views: </b>" . $row['view_count'] ?></p>
                    </div>
                    <div class="floatDiv">
                        <p><?php echo "<b>Clip length: </b>" . $row['duration'] ?></p>
                    </div>
                    <div class="floatDiv">
                        <p><?php echo "<b>Created at: </b>" . $row['created_at'] ?></p>
                    </div>
                    <div class="floatDiv">
                        <p><?php echo "<b>Added to DB: </b>" . $row['added'] ?></p>
                    </div>
                    <div class="floatDiv">
                        <p><?php echo "<b>Available in Twitch: </b>" . $row['available_twitch'] ?></p>
                    </div>
                    <div class="floatDiv">
                        <p><?php echo "Clip id: " . $row['name'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="playerDiv">
            <div class="videoPlayers">
                <iframe src="https://clips.twitch.tv/embed?clip=<?php echo $row['name'] ?>&parent=onlymans.site&parent=www.onlymans.site" preload="metadata" autoplay="false" height="720" width="1280" allowfullscreen>
                </iframe>
            </div>
        </div>
        <?php echo file_get_contents("html/footer.html"); ?>