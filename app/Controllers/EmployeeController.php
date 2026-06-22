<?php

require_once __DIR__ . '/../../config/database.php';
class EmployeeController
{
    public function store()
    {
        global $conn;

        $employeeCode = $_POST['employee_code'];
        $employeeName = $_POST['employee_name'];
        $phoneNumber = $_POST['employee_number'];
        $department = $_POST['department'];

        $sql = "
        INSERT INTO employees(
            employee_code,
            employee_name,
            phone_number,
            department
        )
        VALUES(
            '$employeeCode',
            '$employeeName',
            '$phoneNumber',
            '$department'
        )";

        if (mysqli_query($conn, $sql)) {
            echo "Employee Saved Successfully";
        } else {
            echo mysqli_error($conn);
        }
    }
}
