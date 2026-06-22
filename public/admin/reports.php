<?php

require_once '../../../app/Services/ReportService.php';

$reportService =
    new ReportService();

require_once '../../config/database.php';

$month = $_GET['month'] ?? '';

$data = null;

if ($month) {

    $sql = "

    SELECT
        e.employee_name,
        COUNT(a.id) AS present_days
    FROM employees e
    LEFT JOIN attendance a
    ON e.id = a.employee_id
    AND DATE_FORMAT(a.attendance_date, '%Y-%m') = '$month'
    GROUP BY e.id, e.employee_name

    ";

    $data = mysqli_query($conn, $sql);
}
