<?php

$host = "localhost";
$dbname = "attendence_tracker";
$user = "root";
$password = "";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $dbname
);
if ($conn->connect_error) {
    die("Connection Failed");
}
