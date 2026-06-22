<form method="GET">

    From Date

    <input
        type="date"
        name="from_date">

    To Date

    <input
        type="date"
        name="to_date">

    <th>Worked Hours</th>
    <th>Balance</th>
    <th>Location Status</th>

    <button type="submit">
        Search
    </button>

</form>

<br>

<?php

require_once '../../../config/database.php';

$fromDate = $_GET['from_date'] ?? '';

$toDate = $_GET['to_date'] ?? '';

$where = '';

if ($fromDate && $toDate) {

    $where =
        "WHERE a.attendance_date
     BETWEEN '$fromDate'
     AND '$toDate'";
}

$sql = "

SELECT

e.employee_name,

a.attendance_date,

a.check_in,

a.check_out,

a.latitude,

a.longitude
a.location_status

FROM attendance a

INNER JOIN employees e

ON e.id = a.employee_id

$where

ORDER BY a.id DESC

";

$result = mysqli_query(
    $conn,
    $sql
);
