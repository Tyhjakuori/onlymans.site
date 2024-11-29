<?php
include_once("../auth.php");
include_once(__DIR__ . "/../../config.php");

$game_name = $game_name_dirty = $game_id = $game_id_dirty = "";
$game_name_dirty_err = $game_id_dirty_err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION["username"];
    $game_name_dirty = $_POST["game_name"];
    if (empty($game_name_dirty)) {
        $game_name_dirty = "Please enter a game name";
    } else if (!preg_match("/^[a-zA-Z0-9_': ]+$/", $game_name_dirty)) {
        $game_name_dirty_err = "Game name can only contain letters, numbers, underscores and spaces";
    } else {
        $sql = "SELECT id FROM check_streams WHERE game_name = ? AND user = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $game_name_dirty, $username);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows === 1) {
                    $game_name_dirty_err = "Game is already added";
                    echo "Already added";
                } else {
                    $game_name = $game_name_dirty;

                    $sql2 = "SELECT id FROM game_ids WHERE name = ?";
                    $stmt = $conn->prepare($sql2);
                    $stmt->bind_param("s", $game_name);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $game_id = $result->fetch_row()[0] ?? NULL;
                    if ($game_id === NULL) {
                        $game_id_err = "Couldn't find game in the database";
                    }
                }
            } else {
                echo "Something went wrong.";
            }
            $stmt->close();
        }
    }

    if (empty($game_name_dirty_err) && $game_id !== NULL) {
        $sql = "INSERT INTO check_streams (user, game_name, game_id) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $username, $game_name, $game_id);
            try {
                if ($stmt->execute()) {
                    echo "Success";
                    header("location: add_id.php");
                } else {
                    echo "Something went wrong";
                }
            } catch (Exception $e) {
                echo "Something went wrong";
            }
            $stmt->close();
        }
    }
}

$resp = mysqli_query($conn, "SELECT id,name FROM game_ids");

$sql3 = "SELECT game_name FROM check_streams WHERE user = ?";
$stmt = $conn->prepare($sql3);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$already_added = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <title>Add games to your check livestreams page | OnlyMans</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="description" content="Add games to your check streams page">
    <meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1">
    <link rel="icon" href="../public/favicon.svg">
    <link rel="stylesheet" href="../css/add_ids_styles.css" type="text/css">
    <link rel="alternate" type="application/rss+xml" title="OnlyMans site news" href="../rss.xml">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="../sitemap.xml">
</head>

<body>
    <?php echo file_get_contents("../html/navigation.html"); ?>
    <div id="main-cont">
        <div class="add_games">
            <div class="added_games">
                <?php
                if (empty($already_added->num_rows)) {
                    echo "<p>You haven't added any games yet</p>";
                } else {
                    echo "<h3>You have added following games</h3>";
                    while (($added = mysqli_fetch_array($already_added))) {
                        echo "<p>{$added['game_name']}</p>";
                    }
                } ?>
                <br />
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
                <label>Type gamename to add</label>
                <input list="game_names" name="game_name">
                <datalist id="game_names">
                    <?php
                    while ($fetch = mysqli_fetch_array($resp)) {
                        echo "<option data-value=value=\"" . $fetch['name'] . "\">" . $fetch['name'] . "</option>";
                    }
                    ?>
                </datalist>
                <br />
                <?php if (!empty($game_name_dirty_err)) echo "<span class='invalid-feedback'>{$game_name_dirty_err}</span><br />"; ?>
                <?php if (!empty($game_id_err)) echo "<span class='invalid-feedback'>{$game_id_err}</span><br />"; ?>
                <br /><input type="submit" value="Add game">
            </form>
        </div>
        <p>Please keep in mind the more games you add the longer it will take to load the check streams page</p>
        <p>Also i'd appriciate it if the check streams page wasn't repeatedly refreshed as it could get my api key revoked</p>
        <p>If the api key gets revoked, that would be end of OnlyMans. So i'm placing my trust in you</p>
    </div>

    <?php
    echo file_get_contents("../html/footer.html");
    $conn->close();
    ?>
