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
        <?php echo file_get_contents("html/navigation.html"); ?>
        <div class="main">
            <br />
            <h3>Clips created per year</h3>
            <img src="charts/clips_created_year.php" alt="clips created per year" />
            <br />
            <h3>Clips created month-year</h3>
            <img src="charts/clips_created_month_year.php" alt="clips created month-year" />
            <br />
            <h3>Highlights created per year</h3>
            <img src="charts/highlights_created_year.php" alt="highlights created per year" />
            <br />
            <h3>Highlights created month-year</h3>
            <img src="charts/highlights_created_month_year.php" alt="highlights created month-year" />
            <br />
            <h3>Amount of clips created by user(top 30)</h3>
            <img src="charts/clips-per-user.php" alt="Amount of clips created by user" />
            <br />
        <?php echo file_get_contents("html/footer.html"); ?>
