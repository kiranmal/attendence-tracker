<?php

session_start();

require_once '../../config/database.php';

$attendanceId = $_POST['attendance_id'];

$employeeId = $_SESSION['employee_id'];

$task1 = $_POST['task1'];
$task2 = $_POST['task2'];
$task3 = $_POST['task3'];

mysqli_query(
    $conn,
    "INSERT INTO daily_tasks
    (
        attendance_id,
        employee_id,
        task1,
        task2,
        task3
    )
    VALUES
    (
        '$attendanceId',
        '$employeeId',
        '$task1',
        '$task2',
        '$task3'
    )"
);

echo "success";
