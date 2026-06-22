<?php

session_start();

require_once '../../config/database.php';

if (!isset($_SESSION['employee_id'])) {
    die('Please Login First');
}

$employeeId = $_SESSION['employee_id'];

$result = mysqli_query(
    $conn,
    "
    SELECT employee_name
    FROM employees
    WHERE id = '$employeeId'
    LIMIT 1
    "
);

$user = mysqli_fetch_assoc($result);



?>

<!DOCTYPE html>
<html>

<head>
    <title>Employee Dashboard</title>
</head>

<body>

    <h2>
        Welcome
        <?= $_SESSION['employee_name'] ?>
    </h2>

    <hr>

    <h3>Attendance Menu</h3>

    <a href="/pcl_attendence_sheet/app/Views/attendence/my_attendance.php">
        My Attendance
    </a>

    <br><br>

    <a href="/pcl_attendence_sheet/public/employee/checkin_form.php">
        Check In
    </a>

    <br><br>

    <a href="/pcl_attendence_sheet/public/employee/checkout.php">
        Check Out
    </a>

    <br><br>

    <a href="/pcl_attendence_sheet/public/logout.php">
        Logout
    </a>

</body>

</html>