<?php

require_once '../../../config/database.php';

$totalEmployees = mysqli_fetch_row(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) FROM employees"
    )
)[0];

$presentToday = mysqli_fetch_row(
    mysqli_query(
        $conn,
        "
SELECT COUNT(DISTINCT employee_id)
FROM attendance
WHERE attendance_date = CURDATE()
"
    )
)[0];

?>

<h2>Total Employees:
    <?= $totalEmployees ?>
</h2>

<h2>Present Today:
    <?= $presentToday ?>
</h2>

<h2>Absent Today:
    <?= $totalEmployees - $presentToday ?>
</h2>