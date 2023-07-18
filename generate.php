<?php

exec('cd python && python generate_title.py', $python);
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="UTF-8">
    <title>Generate title | OnlyMans</title>
    <meta name="description" contents="Site for OnlyMans">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/generate_styles.css" type="text/css">
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
        <div class="result">
            <h2 class="title2">Generate a stream title!</h2>
            <a href="./generate.php">Reload to try again</a>
            <blockquote><?php echo $python[0] ?></blockquote>
        </div>
    </div>

    <?php echo file_get_contents("html/footer.html"); ?>