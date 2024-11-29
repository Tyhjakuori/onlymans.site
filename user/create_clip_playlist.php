<?php
include_once(__DIR__ . "/../config.php");

?>
<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="UTF-8">
    <title>OnlyMans</title>
    <meta name="description" contents="Site for OnlyMans">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
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
                            <form action='search.php' method='GET'>
                                <select name="columns">
                                    <option value="name">Clip ID</option>
                                    <option value="title">Clip title</option>
                                    <option value="broadcaster">Broadcaster</option>
                                    <option value="creator_name">Clipper</option>
                                    <option value="game_name">Game name</option>
                                    <option value="game_id">Game ID</option>
                                </select>
                                <input type="text" name="cliparams" placeholder="Search clips...">
                                <input type="submit" value="search clips">
                            </form>
                        </li>
                        <li>
                            <form action='search.php' method='GET'>
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
        <br>
        <div class="wrapper">
            <div class="mainsearch">
                <form action='clips.php' method='POST'>
                    <b>Filter clips page by broadcaster</b><br>
                    <select name="clip_brod">
                        <option value="All">All</option>
                        <?php
                        while ($clip_brod = mysqli_fetch_array($resp_clips)) {
                            echo "<option value='{$clip_brod['broadcaster']}'>";
                            echo $clip_brod['broadcaster'];
                            echo "</option>";
                        } ?>
                    </select>
                    <input type='submit' value='filter'>
                </form>
                <br>
                <form action='highlights.php' method='POST'>
                    <b>Filter highlights page by broadcaster</b><br>
                    <select name="high_brod">
                        <option value="All">All</option>
                        <?php
                        while ($high_brod = mysqli_fetch_array($resp_high)) {
                            echo "<option value='{$high_brod['user_name']}'>";
                            echo $high_brod['user_name'];
                            echo "</option>";
                        } ?>
                    </select>
                    <input type='submit' value='filter'>
                </form>
            </div>
        </div>
    </div>
    <?php echo file_get_contents("html/footer.html"); ?>