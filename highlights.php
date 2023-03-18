<?php
include_once(__DIR__ . "/../config.php");
if(isset($_POST['high_brod'])) {
	$caster = mysqli_real_escape_string($conn, $_POST['high_brod']);
	if ($caster === 'All') {
		$sql = "SELECT * FROM highlights";
		$resp = mysqli_query($conn, $sql);
	} else {
		$sql = "SELECT * FROM highlights WHERE user_name = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $caster);
		$stmt->execute();
		$resp = $stmt->get_result();
	}
} else {
	$resp = mysqli_query($conn, "SELECT * FROM highlights");
}
$row_cnt = $resp->num_rows;
$resp2 = mysqli_query($conn, "SELECT updated FROM db_updated WHERE table_name='highlights' ORDER BY updated DESC LIMIT 1");
$resp2_row = mysqli_fetch_array($resp2);
?>
<!DOCTYPE html>
<html lang=en>
	<head>
		<meta charset="UTF-8">
		<title>Highlights | OnlyMans</title>
		<meta name="description" contents="Site for OnlyMans">
		<link rel="icon" href="public/favicon.svg">
		<link rel="stylesheet" href="css/highlights_styles.css" type="text/css">
	</head>
	<body>
		<header class="nav">		
			<nav class="navigation">
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="clips.php">Clips</a></li>
					<li><a href="highlights.php">Highlights</a></li>
				</ul>
			</nav>
		</header>
		<div id="main-cont">
		   <table class="highlight_table" border='2' align='center'>
		   		<h3 align='center'>Highlights table</h3>
		   		<h3><?php echo "Number of results: ".$row_cnt; ?></h3>
				<h4><?php echo "Last updated: ".$resp2_row['updated']; ?></h4>
				<thead>
					<tr>
						<th>URL</th>
						<th>Title</th>
						<th>Broadcaster</th>
						<th>Description</th>
						<th>Viewable</th>
						<th>Date</th>
						<th>Duration</th>
						<th>Added to DB</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while ($fetch = mysqli_fetch_array($resp)) {
						echo "<tr>";
						echo "<td><a href=\"https://www.twitch.tv/videos/{$fetch['url']}\" target=\"_blank\">" . $fetch['url'] . "</a></td>";
						echo "<td><a href=\"highlight.php?id={$fetch['id']}\">" . $fetch['title'] . "</a></td>";
						echo "<td>" . $fetch['user_name'] . "</td>";
						echo "<td>" . $fetch['description'] . "</td>";
						echo "<td>" . $fetch['viewable'] . "</td>";
						echo "<td>" . $fetch['created_at'] . "</td>";
						echo "<td>" . $fetch['duration'] . "</td>";
						echo "<td>" . $fetch['added'] . "</td>";
						echo "</tr>";
					}?>
				</tbody>
			</table>
			<script src="js/jQuery.js" type="text/javascript"></script>
			<script src="js/sorter.js" type="text/javascript"></script>

<?php echo file_get_contents("html/footer.html");?>
