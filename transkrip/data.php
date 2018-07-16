<?php
session_start();

require_once "../src/database.php";

if (isset($_SESSION['userSessionID']) == "" && isset($_SESSION['userSessionName']) == "" && isset($_SESSION['userSessionEmail']) == "") {
    header("location: /403.php");
}

$id = $_SESSION['userSessionID'];

$sql = "SELECT @n := 0 m";
$res = mysqli_query($db, $sql) or die(mysqli_error($db));
$sql = "SELECT @n := @n + 1 n, t.*, u.id AS id_notulis, u.nama AS user_name FROM transkrip t JOIN notulis u ON u.id = t.id_notulis WHERE u.id = '$id'";
$res = mysqli_query($db, $sql) or die(mysqli_error($db));

$data = array();

while ($row = mysqli_fetch_assoc($res)) {
    $data[] = $row;
}

$i = 0;

foreach ($data as $key) {
    $data[$i]['button'] = '<button type="submit" id="' . $data[$i]['id'] . '" class="btn btn-xs btn-info btn-view" ><i class="fa fa-eye"></i></button> <button type="submit" id="' . $data[$i]['id'] . '" class="btn btn-xs btn-warning btn-edit" ><i class="fa fa-edit"></i></button> <button type="submit" id="' . $data[$i]['id'] . '" class="btn btn-xs btn-danger btn-delete-transcript" ><i class="fa fa-trash"></i></button>';
    $i++;
}

$jsonData = array('data' => $data);

echo json_encode($jsonData);
?>
