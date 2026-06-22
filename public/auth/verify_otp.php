<?php

session_start();

require_once '../../config/database.php';

$mobile = trim($_POST['phone_number']);
$otp = trim($_POST['otp']);
$action = $_POST['action'] ?? '';

$deviceToken = $_POST['device_token'] ?? '';
$result = mysqli_query(
    $conn,
    "
    SELECT *
    FROM employees
    WHERE phone_number='$mobile'
    AND otp='$otp'
    AND otp_expiry > NOW()
    "
);



if (mysqli_num_rows($result) > 0) {

    $employee = mysqli_fetch_assoc($result);

    /*
    |-----------------------------------
    | Register Device (First Login Only)
    |-----------------------------------
    */
    if (
        empty($employee['device_token'])
        &&
        !empty($deviceToken)
    ) {

        mysqli_query(
            $conn,
            "
            UPDATE employees
            SET device_token='$deviceToken'
            WHERE id=" . (int)$employee['id']
        );

        $employee['device_token'] = $deviceToken;
    }

    /*
    |-----------------------------------
    | Block Other Devices
    |-----------------------------------
    */
    if (
        !empty($employee['device_token'])
        &&
        $employee['device_token'] != $deviceToken
    ) {

        die('This account is registered on another device.');
    }



    /*
    |-----------------------------------
    | Login Session
    |-----------------------------------
    */
    $_SESSION['employee_id'] =
        $employee['id'];

    $_SESSION['employee_name'] =
        $employee['employee_name'];

    /*
    |-----------------------------------
    | Admin Check
    |-----------------------------------
    */
    $adminResult = mysqli_query(
        $conn,
        "
        SELECT *
        FROM users
        WHERE phone_number='$mobile'
        AND role='admin'
        "
    );
    $admin = mysqli_fetch_assoc($adminResult);


    if ($action == 'admin') {

        $adminResult = mysqli_query(
            $conn,
            "
        SELECT *
        FROM users
        WHERE phone_number='$mobile'
        AND role='admin'
        "
        );

        $admin = mysqli_fetch_assoc($adminResult);

        $_SESSION['admin_id'] = $admin['id'];

        header(
            'Location: /pcl_attendence_sheet/app/views/admin/dashboard.php'
        );
        exit;
    } else {

        $_SESSION['employee_id'] = $employee['id'];

        header(
            'Location: /pcl_attendence_sheet/app/views/employees/dashboard.php'
        );
        exit;
    }
}

echo "Invalid OTP";
