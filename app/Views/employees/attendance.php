<?php

require_once '../../../config/database.php';

$result = mysqli_query(
    $conn,
    "
    SELECT *
    FROM attendance
    ORDER BY id DESC
    "
);

while ($row = mysqli_fetch_assoc($result)) {
    echo $row['attendance_date'];
    echo '<br>';
}
