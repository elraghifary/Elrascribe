<?php
session_start();

require_once "../src/database.php";

if (isset($_SESSION['adminSessionID']) == "" && isset($_SESSION['adminSessionName']) == "" && isset($_SESSION['adminSessionEmail']) == "") {
    header("location: ./");
}

if (isset($_SESSION['adminSessionID']) != "" && isset($_SESSION['adminSessionName']) != "" && isset($_SESSION['adminSessionEmail']) != "") {
    unset($_SESSION['adminSessionID']);
    unset($_SESSION['adminSessionName']);
    unset($_SESSION['adminSessionEmail']);
    header("location: ./");;
}
?>
