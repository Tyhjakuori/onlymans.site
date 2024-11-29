<?php
include_once(__DIR__ . "/../../config.php");
if (isset($_POST['highlight_ids']) && isset($_POST['playlist_name'])) {
	$dirty_ids = $_POST['highlight_ids'];
	$dirty_playlistname = $_POST['playlist_name'];
	$dirty_description = $_POST['description'];

	$sql = "";
	if (empty($dirty_description)) {
		$sql = "INSERT INTO playlists (name, highlight_id, creator, playlist_type) VALUES (?, ?, ?, ?)";
	} else {
		$sql = "INSERT INTO playlists (name, highlight_id, creator, description, playlist_type) VALUES (?, ?, ?, ?, ?)";
	}
	$stmt = $conn->prepare($sql);

	foreach ($dirty_ids as $dirty_id) {
		$all_vals = [];
		$stmt->bind_params(str_repeat('s', count($all_vals)), ...$all_vals);
		$stmt->execute();
	}

	print_r($dirty_ids);
	print_r($dirty_playlistname);
}
