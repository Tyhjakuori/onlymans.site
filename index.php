<?php
include_once(__DIR__ . "/../config.php");
$resp_clips = $conn->query("SELECT broadcaster FROM clips GROUP BY broadcaster HAVING COUNT(broadcaster) > 1 ORDER BY broadcaster ASC");
$resp_high = $conn->query("SELECT user_name FROM highlights GROUP BY user_name HAVING COUNT(user_name) > 1 ORDER BY user_name ASC");
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <title>OnlyMans</title>
    <meta name="description" contents="Site for OnlyMans">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
</head>

<body>
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
    <br>
    <form action='clips.php' method='POST'>
        <b>Search clips</b><br>
        <select name="clip_brod">
            <option value="All">All</option>
            <?php
            while ($clip_brod = mysqli_fetch_array($resp_clips)) {
                echo "<option value='{$clip_brod['broadcaster']}'>";
                echo $clip_brod['broadcaster'];
                echo "</option>";
            } ?>
        </select>
        <input type='submit' value='search'>
    </form>
    <br>
    <form action='highlights.php' method='POST'>
        <b>Search highlights</b><br>
        <select name="high_brod">
            <option value="All">All</option>
            <?php
            while ($high_brod = mysqli_fetch_array($resp_high)) {
                echo "<option value='{$high_brod['user_name']}'>";
                echo $high_brod['user_name'];
                echo "</option>";
            } ?>
        </select>
        <input type='submit' value='search'>
    </form>

    <?php echo file_get_contents("html/footer.html"); ?>