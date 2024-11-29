<?php
include_once(__DIR__ . "/../../config.php");
include_once("../auth.php");

?>
<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="UTF-8">
    <title>Dashboard | OnlyMans</title>
    <meta name="description" contents="Site for OnlyMans">
    <link rel="icon" href="/public/favicon.svg">
    <link rel="stylesheet" href="/css/user/dashboard_styles.css" type="text/css">
</head>

<body>
    <div class="main-cont">
        <?php echo file_get_contents("../html/navigation.html"); ?>
        <p><?php echo "Hello " . $_SESSION['username']; ?></p>
        <a href="/user/check_streams.php">Check streams</a><span>All registered users</span><br />
        <a href="/user/add_id.php">Add games to check streams</a><span>All registered users</span><br />
        <a href="/user/playlists.php">Browse playlists</a><span>Does not work yet | All users</span><br />
        <a href="/user/add_rss.php">Add new rss entry</a><span>Does not work yet | Admin</span><br />
        <a href="/user/modify_clips.php">Modify clip information</a><span>Does not work yet | Admin / trusted user</span><br />
        <a href="/user/modify_highlights.php">Modify highlight information</a><span>Does not work yet | Admin / trusted user</span>
        <?php echo file_get_contents("../html/footer.html"); ?>