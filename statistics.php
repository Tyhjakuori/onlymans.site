<!DOCTYPE html>
<html lang=en>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Statistics | OnlyMans</title>
    <meta name="description" content="Statistics of OnlyMans clips and highlights">
    <meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1" />
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/statistics_styles.css" type="text/css">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
    <link rel="alternate" type="application/rss+xml" title="OnlyMans site news" href="/rss.xml">
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
                    <li><a href="statistics.php">Statistics</a></li>
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
        <div class="main">
            <br />
            <h3>Clips created month-year</h3>
            <img src="charts/clips_created_month_year.php" alt="clips created month-year" />
            <br />
            <h3>Highlights created month-year</h3>
            <img src="charts/highlights_created_month_year.php" alt="highlights created month-year" />
            <br />
        <?php echo file_get_contents("html/footer.html"); ?>