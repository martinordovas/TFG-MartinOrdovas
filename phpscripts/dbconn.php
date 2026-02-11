<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$host = "localhost";
$user = "root";
$pass = "ordovas2005";
$db_name = "skillnet";

$conn = mysqli_connect($host, $user, $pass, $db_name);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>