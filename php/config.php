<?php
$host = "localhost:5222";
$user = "root";
$pass = "";
$db = "safesteps";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>