<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Repositories/AttendanceRepository.php';

class ReportController
{
    public function monthlyReport()
    {
        global $conn;

        $repo = new AttendanceRepository();

        $result = $repo->getMonthlyAttendance($conn);

        // pass variable to view
        include __DIR__ . '/../Views/reports/monthly.php';
    }
}
