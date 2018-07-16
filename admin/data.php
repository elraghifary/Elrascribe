<?php
session_start();

require_once "../src/database.php";

if (isset($_SESSION['adminSessionID']) == "" && isset($_SESSION['adminSessionName']) == "" && isset($_SESSION['adminSessionEmail']) == "") {
    header("location: /403.php");
}

$sql = "SELECT @n := 0 m";
$res = mysqli_query($db, $sql) or die(mysqli_error($db));
$sql = "SELECT @n := @n + 1 n, id, nama, email, telepon, status FROM notulis";
$res = mysqli_query($db, $sql) or die(mysqli_error($db));

$data = array();

while ($row = mysqli_fetch_assoc($res)) {
    $data[] = $row;
}

$i = 0;

foreach ($data as $key) {
    $data[$i]['button'] = '<button type="submit" id="' . $data[$i]['id'] . '" class="btn btn-xs btn-danger btn-delete-notulis" ><i class="fa fa-trash"></i></button>';
    $i++;
}

$jsonData = array('data' => $data);

echo json_encode($jsonData);
?>
