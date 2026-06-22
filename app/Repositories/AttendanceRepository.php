<?php

class AttendanceRepository
{
    public function getMonthlyAttendance($conn)
    {
        $sql = "
        SELECT
            e.employee_name,
            COUNT(a.id) AS present_days,
            a.location_status
        FROM employees e
        LEFT JOIN attendance a
        ON e.id = a.employee_id
        GROUP BY e.id
        ";

        return mysqli_query($conn, $sql);
    }
}
