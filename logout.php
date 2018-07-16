<?php
session_start();

require_once "src/database.php";

if (isset($_SESSION['userSessionID']) != "" && isset($_SESSION['userSessionName']) != "" && isset($_SESSION['userSessionEmail']) != "") {
    header("location: /login.php");
}

if (isset($_SESSION['userSessionID']) != "" && isset($_SESSION['userSessionName']) != "" && isset($_SESSION['userSessionEmail']) != "") {
    unset($_SESSION['userSessionID']);
    unset($_SESSION['userSessionName']);
    unset($_SESSION['userSessionEmail']);
    header("location: /login.php");;
}
?>
