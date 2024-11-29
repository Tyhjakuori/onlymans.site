<?php
include_once("auth.php");
include_once(__DIR__ . "/../config.php");

$game_name = $game_name_dirty = $game_id = $game_id_dirty = "";
$game_name_dirty_err = $game_id_dirty_err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION["username"];
    $game_name_dirty = $_POST["game_name"];
    if (empty($game_name_dirty)) {
        $game_name_dirty = "Please enter a game name";
    } else if (!preg_match('/^[a-zA-Z0-9_ ]+$/', $game_name_dirty)) {
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
$conn->close();
?>
<!DOCTYPE html>
<html lang=en>

<body>
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
            <input type="submit" value="Add game">
    </form>
</body>

</html>
