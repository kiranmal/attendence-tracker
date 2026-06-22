<?php

require_once '../../config/database.php';
include('../../functions.php');

$mobile = trim($_POST['phone_number']);

if (!preg_match('/^[0-9]{10}$/', $mobile)) {
    die('Invalid mobile number');
}

$result = mysqli_query(
    $conn,
    "SELECT * FROM employees
     WHERE phone_number='$mobile'"
);

if (mysqli_num_rows($result) == 0) {

    header(
        "Location: /pcl_attendence_sheet/app/views/auth/login.php?error=access_denied"
    );

    exit;
}

$employee = mysqli_fetch_assoc($result);

$deviceToken =
    $_POST['device_token'] ?? '';

//$otp = rand(1000, 9999);


if (
    !empty($employee['device_token'])
    &&
    $employee['device_token']
    != $deviceToken
) {

    die('This mobile number is registered on another device.');
}
date_default_timezone_set('Asia/Kolkata');

$expiry = date(
    "Y-m-d H:i:s",
    strtotime("+5 minutes")
);


$waToken = sendWhatsAppOTP($mobile);



$waotp = $waToken['otp'] ?? '';



$sql = "
UPDATE employees
SET
    otp='$waotp',
    otp_expiry='$expiry'

WHERE phone_number='$mobile'
";

mysqli_query($conn, $sql);

echo "OTP: " . $waotp;
echo "<pre>";
print_r($waotp);
// exit;


header(
    'Location: /pcl_attendence_sheet/app/views/auth/verify_otp.php?mobile='
        . $mobile
        . '&action=employee'
);

exit;
