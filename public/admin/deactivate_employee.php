<?php

require_once '../../config/database.php';

$id = (int)$_GET['id'];

mysqli_query(
    $conn,
    "
    UPDATE employees
    SET status='inactive'
    WHERE id=$id
    "
);

header(
    'Location: /pcl_attendence_sheet/app/views/admin/employees.php'
);

exit;
