<?php

session_start();
$_SESSION = [];

session_unset();

session_destroy();

header(
    'Location: /pcl_attendence_sheet/app/views/auth/login.php'
);

exit;
