<?php

require_once '../../config/database.php';
include('../../functions.php');

$mobile = trim($_POST['phone_number']);

if (!preg_match('/^[0-9]{10}$/', $mobile)) {
    die('Invalid mobile number');
}

$checkEmployee = mysqli_query(
    $conn,
    "SELECT id FROM users WHERE phone_number = '$mobile'"
);

if (mysqli_num_rows($checkEmployee) == 0) {
    header(
        "Location: /pcl_attendence_sheet/app/views/auth/login_user----.php?error=access_denied"
    );

    exit;
}

//$otp = rand(1000, 9999);

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
        . '&action=admin'
);

exit;
