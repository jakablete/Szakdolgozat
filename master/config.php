<?php

$name= "localhost";
$uname= "root";
$pass= "";

$db_name= "basketball_stat";

$conn = mysqli_connect($name, $uname, $pass, $db_name);

if (!$conn) {
    die("Connection failed: ");
} else {
	mysqli_set_charset($conn, 'utf8mb4');
	$conn->query("SET NAMES 'UTF8'");
}

?>