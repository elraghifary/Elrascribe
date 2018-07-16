<?php
date_default_timezone_set('Asia/Jakarta');

$dbHost = 'DB_HOST';
$dbUsername = 'DB_USER';
$dbPassword = 'DB_PASS';
$dbName = 'DB_NAME';

$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

?>
