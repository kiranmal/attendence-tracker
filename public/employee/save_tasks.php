<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['employee_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Please login"
    ]);
    exit;
}

$employeeId = $_SESSION['employee_id'];

$attendanceId = (int)$_POST['attendance_id'];

$task1 = mysqli_real_escape_string($conn, $_POST['task1']);
$task2 = mysqli_real_escape_string($conn, $_POST['task2']);
$task3 = mysqli_real_escape_string($conn, $_POST['task3']);

// Check if today's tasks already exist
$check = mysqli_query($conn, "
SELECT id
FROM daily_tasks
WHERE attendance_id='$attendanceId'
");

if (mysqli_num_rows($check) > 0) {

    mysqli_query($conn, "
    UPDATE daily_tasks
    SET
    task1='$task1',
    task2='$task2',
    task3='$task3'
    WHERE attendance_id='$attendanceId'
    ");
} else {

    mysqli_query($conn, "
    INSERT INTO daily_tasks
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
    )
    ");
}

echo json_encode([
    "success" => true
]);
