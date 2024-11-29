<?php
if (isset($_GET['id'])) {
    include_once(__DIR__ . "/../config.php");
    $id = $_GET['id'];
    $sani_id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    if (filter_var($sani_id, FILTER_VALIDATE_INT) === 0 || !filter_var($sani_id, FILTER_VALIDATE_INT) === false) {
        $sql = "SELECT * FROM clips WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $sani_id);
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
        $stmt->bind_param("s", $sani_clip);
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
    <title><?php echo $row['title'] . " | OnlyMans" ?></title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="description" content="<?php echo "&quot;" . $row['title'] . "&quot; by: " . $row['broadcaster']; ?>">
	<meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1">
	<link rel="icon" href="public/favicon.svg">
	<link rel="stylesheet" href="css/clip_styles.css" type="text/css">
	<link rel="alternate" type="application/rss+xml" title="OnlyMans site news" href="/rss.xml">
	<link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">	
</head>

<body>
	<div id="main-cont">
        <?php echo file_get_contents("html/navigation.html"); ?>
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
				<iframe
					src="https://clips.twitch.tv/embed?clip=<?php echo $row['name']?>&parent=127.0.0.1"
					preload="metadata"
					autoplay="false"
					height="720"
					width="1280"
					allowfullscreen>
				</iframe>
			</div>
		</div>
<?php echo file_get_contents("html/footer.html"); ?>


