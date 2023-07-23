<?php
if (isset($_GET['id'])) {
	include_once(__DIR__ . "/../config.php");
	$id = mysqli_real_escape_string($conn, $_GET['id']);
	$sani_id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
	if (filter_var($sani_id, FILTER_VALIDATE_INT) === 0 || !filter_var($sani_id, FILTER_VALIDATE_INT) === false) {
		$sql = "SELECT * FROM highlights WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$resu = $stmt->get_result();
		$row = $resu->fetch_assoc();
	} else {
		header('Location: highlights.php');
		die();
	}
} elseif (isset($_GET['url'])) {
	include_once(__DIR__ . "/../config.php");
	$url_id = $_GET['url'];
	$sani_url = filter_var($url_id, FILTER_SANITIZE_NUMBER_INT);
	if (filter_var($sani_url, FILTER_VALIDATE_INT) === 0 || !filter_var($sani_url, FILTER_VALIDATE_INT) === false) {
		$sql = "SELECT * FROM highlights WHERE url = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $sani_url);
		$stmt->execute();
		$resu = $stmt->get_result();
		$row = $resu->fetch_assoc();
	} else {
		header('Location: highlights.php');
		die();
	}
} else {
	header('Location: highlights.php');
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
	<link rel="stylesheet" href="css/highlight_styles.css" type="text/css">
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
	<div id="main-cont">
		<div class="highInfo">
			<h1><?php echo "Highlight title: " . $row['title'] ?></h1>
			<div class="boxDiv">
				<div class="highDiv">
					<div class="floatDiv">
						<p><?php echo "<b>Broadcaster: </b><a href='https://www.twitch.tv/" . $row['user_name'] . "' target='_blank'>" . $row['user_name'] . "</a>" ?></p>
					</div>
					<div class="floatDiv">
						<p><?php echo "<b>Description: </b>" . $row['description'] ?></p>
					</div>
					<div class="floatDiv">
						<p><?php echo "<b>Length: </b>" . $row['duration'] ?></p>
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
				</div>
			</div>
			<div class="playerDiv">
				<div class="videoPlayers">
					<iframe src="https://player.twitch.tv/?video=<?php echo $row['url'] ?>&parent=onlymans.site&parent=www.onlymans.site" preload="metadata" autoplay="false" height="720" width="1280" allowfullscreen>
					</iframe>
				</div>
			</div>

			<?php echo file_get_contents("html/footer.html"); ?>