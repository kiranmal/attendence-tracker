<?php

require_once '../../config/database.php';

$employeeCode = $_POST['employee_code'];
$employeeName = $_POST['employee_name'];
$phoneNumber = $_POST['phone_number'];
$department = $_POST['department'];

$sql = "
INSERT INTO employees
(
employee_code,
employee_name,
phone_number,
department,
status
)
VALUES
(
'$employeeCode',
'$employeeName',
'$phoneNumber',
'$department',
'active'
)
";

mysqli_query($conn, $sql);

header(
    'Location: /pcl_attendence_sheet/app/views/admin/employees.php'
);
