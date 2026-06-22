<?php

session_start();

require_once '../../config/database.php';

$username = $_POST['username'];
$password = md5($_POST['password']);

$sql = "
SELECT *
FROM users
WHERE username='$username'
AND password='$password'
";



$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    $_SESSION['admin_id'] = $user['id'];


    header(
        'Location: /pcl_attendence_sheet/app/views/admin/dashboard.php'
    );

    exit;
}

echo "Invalid Login";
