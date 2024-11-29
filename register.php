<?php
include_once(__DIR__ . "/../config.php");

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $trimmed_user = $_POST["username"];
    if (empty($trimmed_user)) {
        $username_err = "Please enter a username";
    } else if (!preg_match('/^[a-zA-Z0-9_]+$/', $trimmed_user)) {
        $username_err = "Username can only contain letters, numbers and underscores";
    } else {
        $sql = "SELECT id FROM accounts WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $trimmed_user);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows === 1) {
                    $username_err = "Username is already taken";
                } else {
                    $username = $trimmed_user;
                }
            } else {
                echo "Something went wrong.";
            }
            $stmt->close();
        }
    }

    $trimmed_pass = trim($_POST["password"]);
    if (empty($trimmed_pass)) {
        $password_err = "Please enter a password";
    } elseif (strlen($trimmed_pass) < 8) {
        $password_err = "Password must be atleast 8 characters";
    } else {
        $password = $trimmed_pass;
    }

    $trimmed_confirm = trim($_POST["confirm_password"]);
    if (empty($trimmed_confirm)) {
        $confirm_password_err = "Please confirm password";
    } else {
        $confirm_password = $trimmed_confirm;
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match";
        }
    }

    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO accounts (username, password) VALUES (?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ss", $username, $param_password);
            try {
                if ($stmt->execute()) {
                    header("location: login.php");
                } else {
                    echo "Something went wrong";
                }
            } catch (Exception $e) {
                echo "Something went wrong";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register | OnlyMans</title>
    <meta name="description" contents="Site for OnlyMans">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/register_styles.css" type="text/css">
</head>

<body>
    <div class="main-cont">
        <?php echo file_get_contents("html/navigation.html"); ?>
        <h1>Register</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
            <ul class="wrapper-ul">
                <li class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" required>
                    <span class="invalid-feedback"><?php echo $username_err ?></span>
                </li>
                <li class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" required>
                    <span class="invalid-feedback"><?php echo $password_err ?></span>
                </li>
                <li class="form-group">
                    <label>Confirm password</label>
                    <input type="password" name="confirm_password" class=class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password ?>" required>
                    <span class="invalid-feedback"><?php echo $confirm_password_err ?></span>
               </li>
                <li class="form-group">
                    <input type="submit" class="submit-btn" value="Create account">
                    <input type="reset" class="reset-btn">
                </li>
                <p>Already have an account? <a href="/login.php">Login here</a></p>
            </ul>
        </form>
    </div>
</body>

</html>