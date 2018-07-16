<?php
session_start();

require_once "../src/database.php";

if (isset($_SESSION['userSessionID']) == "" && isset($_SESSION['userSessionName']) == "" && isset($_SESSION['userSessionEmail']) == "") {
    header("location: /403.php");
}

$id = $_SESSION['userSessionID'];
$projectName = $_POST['projectName'];

$sql = "SELECT idi FROM transkrip WHERE id = (SELECT MAX(id) FROM transkrip WHERE id_notulis = '$id' AND proyek = '$projectName')";
$res = mysqli_query($db, $sql) or die(mysqli_error($db));
$row = mysqli_fetch_assoc($res);

$idi = $row['idi'];

echo $idi;
?>
