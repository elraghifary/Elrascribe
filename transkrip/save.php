<?php
session_start();

require_once "../src/database.php";

if (isset($_SESSION['userSessionID']) == "" && isset($_SESSION['userSessionName']) == "" && isset($_SESSION['userSessionEmail']) == "") {
    header("location: /403.php");
}

$id = $_POST['id'];
$notulis_id = $_SESSION['userSessionID'];
$name = $_POST['name'];
$idi = $_POST['idi'];
$date = $_POST['date'];
$day = $_POST['day'];
$time = $_POST['time'];
$moderator = $_POST['moderator'];
$criteria = $_POST['criteria'];
$body = $_POST['body'];
$crud = $_POST['crud'];

if ($crud == 'N') {
    $sql = "INSERT INTO transkrip(id_notulis, proyek, idi, tanggal, hari, waktu, moderator, kriteria, isi) VALUES('$notulis_id', '$name', '$idi', '$date', '$day', '$time', '$moderator', '$criteria', '$body')";
    $res = mysqli_query($db, $sql) or die(mysqli_error($db));
    if ($res == true) {
        $message['error'] = "";
        $message['result'] = 1;
    } else {
        $message['error'] = mysqli_error($db);
        $message['result'] = 0;
    }
} else if ($crud == 'E') {
    $sql = "UPDATE transkrip SET proyek = '$name', idi = '$idi', tanggal = '$date', hari = '$day', waktu = '$time', moderator = '$moderator', kriteria = '$criteria', isi = '$body' WHERE id = '$id'";
    $res = mysqli_query($db, $sql) or die(mysqli_error($db));
    if ($res == true) {
        $message['error'] = "";
        $message['result'] = 1;
    } else {
        $message['error'] = mysqli_error($db);
        $message['result'] = 0;
    }
} else {
    $message['error'] = 'Undefined.';
    $message['result'] = 0;
}

$message['crud'] = $crud;

echo json_encode($message);
?>
