<?php
include_once(__DIR__ . "/../config.php");
if(isset($_POST['clip_brod'])) {
	$caster = mysqli_real_escape_string($conn, $_POST['clip_brod']);
	if ($caster === "All") {
		$sql = $conn->prepare("SELECT * FROM clips");
		$sql->execute();
		$resp = $sql->get_result();
	} else {
		$sql = "SELECT * FROM clips WHERE broadcaster = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $caster);
		$stmt->execute();
		$resp = $stmt->get_result();
	}
} else {
	$sql = $conn->prepare("SELECT * FROM clips");
	$sql->execute();
	$resp = $sql->get_result();
}
$row_cnt = $resp->num_rows;
$resp2 = mysqli_query($conn, "SELECT updated FROM db_updated WHERE table_name='clips' ORDER BY updated DESC LIMIT 1");
$resp2_row = mysqli_fetch_array($resp2);
?>
<!DOCTYPE html>
<html lang=en>
	<head>
		<meta charset="UTF-8">
		<title>Clips | OnlyMans</title>
		<meta name="description" contents="Site for OnlyMans">
		<link rel="icon" href="public/favicon.svg">
		<link rel="stylesheet" href="css/clips_styles.css" type="text/css">
	</head>
	<body>
		<?php echo file_get_contents("html/navigation.html"); ?>
		<div id="main-cont">
		   <table class="clip_table" border='2' align='center'>
		   		<h3 align='center'>Clips table</h3>
		   		<h3><?php echo "Number of results: ".$row_cnt; ?></h3>
				<h4><?php echo "Last updated: ".$resp2_row['updated']; ?></h4>
				<thead>
					<tr>
						<th>Clip id</th>
		   				<th>Clip title</th>
		   				<th>Broadcaster</th>
		   				<th>Clipper</th>
		   				<th>Game</th>
		   				<th>View count</th>
		   				<th>Duration</th>
		   				<th>Date</th>
		   				<th>Added to DB</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while ($fetch = mysqli_fetch_array($resp)) {
						echo "<tr>";
						echo "<td><a href=\"https://clips.twitch.tv/{$fetch['name']}\" target=\"_blank\">" . $fetch['name'] . "</a></td>";
						echo "<td><a href='clip.php?id={$fetch['id']}'>" . $fetch['title'] . "</a></td>";
						echo "<td>" . $fetch['broadcaster'] . "</td>";
						echo "<td>" . $fetch['creator_name'] . "</td>";
						echo "<td>" . $fetch['game_id'] . "</td>";
						echo "<td>" . $fetch['view_count'] . "</td>";
						echo "<td>" . $fetch['duration'] . "</td>";
						echo "<td>" . $fetch['created_at'] . "</td>";
						echo "<td>" . $fetch['added'] . "</td>";
						echo "</tr>";
					}?>
				</tbody>
			</table>
			<script src="js/jQuery.js" type="text/javascript"></script>
			<script src="js/sorter.js" type="text/javascript"></script>
			
<?php echo file_get_contents("html/footer.html"); ?>
