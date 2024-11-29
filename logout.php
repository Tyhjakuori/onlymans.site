<?php
echo "<!DOCTYPE html>";
echo "<html>";
echo "<body>";
echo "<p>Log out successful</p>";
session_start();
session_destroy();
echo "</body>";
echo "</html>";
header("Location: index.php");
