<?php
if (isset($_GET['playlistid'])) {
    include_once(__DIR__ . "/../../config.php");
    $play_id = $_GET['playlistid'];
    $sani_play = filter_var($play_id, FILTER_SANITIZE_STRING);
    if (!preg_match('/[^a-z_\-0-9]/i', $sani_play)) {
        $sql = "SELECT * FROM playlists WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $sani_play);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();
    } else {
        header('Location: clips.php');
        die();
    }
} else {
    header("Location: playlists.php");
    die();
}
?>
