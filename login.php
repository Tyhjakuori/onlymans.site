<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}
include_once(__DIR__ . "/../config.php");

$username = $password = "";
$username_err = $password_err = $login_err = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $trimmed_user = trim($_POST["username"]);
    if (empty($trimmed_user)) {
        $username_err = "Please enter username";
    } else {
        $username = $trimmed_user;
    }

    $trimmed_pass = trim($_POST["password"]);
    if (empty($trimmed_pass)) {
        $password_err = "Please enter password";
    } else {
        $password = $trimmed_pass;
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id,username,password FROM accounts WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows === 1) {
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            session_regenerate_id();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            header("location: index.php");
                        } else {
                            $login_err = "Invalid username or password";
                        }
                    }
                } else {
                    $login_err = "Invalid username or password";
                }
            } else {
                echo "Something went wrong";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="UTF-8">
    <title>Log in | OnlyMans</title>
    <meta name="description" contents="Site for OnlyMans">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/register_styles.css" type="text/css">
</head>

<body>
    <div class="main-cont">
        <?php echo file_get_contents("html/navigation.html"); ?>
        <h1>Login</h1>
        <form action="/login.php" method="post">
            <ul class="wrapper-ul">
                <li class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" required />
                </li>
                <li class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" required />
                </li>
            </ul>
            <button class="submit-btn" type="submit">Login</button>
        </form>
        <p>New to OnlyMans? <a href="/register.php">Create an account</a></p>
    </div>
    <?php echo file_get_contents("html/footer.html"); ?>