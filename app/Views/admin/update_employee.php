<?php

require_once '../../config/database.php';

$id = (int)$_POST['id'];

$employeeCode = $_POST['employee_code'];

$employeeName = $_POST['employee_name'];

$phoneNumber = $_POST['phone_number'];

$department = $_POST['department'];

$status = $_POST['status'];

$sql = "

UPDATE employees

SET

employee_code='$employeeCode',

employee_name='$employeeName',

phone_number='$phoneNumber',

department='$department',

status='$status'

WHERE id=$id

";

if (mysqli_query($conn, $sql)) {
    header(
        'Location: /pcl_attendence_sheet/app/views/admin/employees.php'
    );
    exit;
}

echo mysqli_error($conn);
