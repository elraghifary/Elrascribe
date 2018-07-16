<?php
session_start();

require_once "../src/database.php";

if (isset($_SESSION['adminSessionID']) == "" && isset($_SESSION['adminSessionName']) == "" && isset($_SESSION['adminSessionEmail']) == "") {
    header("location: /403.php");
}

$id = $_POST['id'];

$sql = "DELETE FROM notulis WHERE id = '$id'";
$res = mysqli_query($db, $sql) or die(mysqli_error($db));

if ($res == true) {
    $message['error'] = "";
    $message['result'] = 1;
} else {
    $message['error'] = mysqli_error($db);
    $message['result'] = 0;
}

echo json_encode($message);
?>
