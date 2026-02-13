<?php
include_once(__DIR__ . "/../config.php");
$resp_clips = $conn->query("SELECT broadcaster FROM clips GROUP BY broadcaster HAVING COUNT(broadcaster) > 1 ORDER BY broadcaster ASC");
$random_clip = $conn->query("SELECT name,broadcaster FROM clips ORDER BY RAND() LIMIT 1");
$row = mysqli_fetch_array($random_clip);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang=en>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>Peggy clips | OnlyMans</title>
		<meta name="description" content="Index page for OnlyMans Peggy experience">
		<meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1" />
		<link rel="icon" href="public/favicon.svg">
		<link rel="stylesheet" href="css/styles.css" type="text/css">
		<link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
	</head>
	<body>
        <?php echo file_get_contents("html/navigation.html"); ?>
		<br>
		 <div class="wrapper">
			</div>
			<noscript>
				<link rel="stylesheet" href="css/noscript.css" type="text/css" />
				<div class="videoplayer">
					<p>There would be a twitch clip here, but it requires javascript unfortunately. Have a video instead!</p>
					<h2>Video of the day brought to you by: MISSPEGGYx</h2>
					<video controls width="1280px" height="720px" preload="metadata" src="/videos/2565.mp4"></video>
				</div>
			</noscript>
			<div id="main-content">
				<div class="clipofday">
					<h2>Random clip of the day brought to you by: <?php echo $row['broadcaster'] ?></h2>
					<iframe src="https://clips.twitch.tv/embed?clip=<?php echo $row['name'] ?>&parent=peggy.onlymans.site" preload="metadata" title="Random clip of day" autoplay="false" height="720" width="1280" allowfullscreen></iframe>

				</div>
			</div>
        </div>

<?php echo file_get_contents("html/footer.html"); ?>

