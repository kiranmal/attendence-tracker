<?php

require_once 'AttendanceService.php';

class ReportService
{
    private $attendanceService;

    public function __construct()
    {
        $this->attendanceService =
            new AttendanceService();
    }

    public function getWorkedHours(
        $checkIn,
        $checkOut
    ) {

        if (
            !$checkIn ||
            !$checkOut
        ) {
            return 'Pending';
        }

        $workedMinutes =
            $this->attendanceService
            ->calculateWorkedMinutes(
                $checkIn,
                $checkOut
            );

        return
            $this->attendanceService
            ->formatDuration(
                $workedMinutes
            );
    }

    public function getDailyBalance(
        $date,
        $checkIn,
        $checkOut
    ) {

        if (
            !$checkIn ||
            !$checkOut
        ) {
            return 'Pending';
        }

        $workedMinutes =
            $this->attendanceService
            ->calculateWorkedMinutes(
                $checkIn,
                $checkOut
            );

        $balance =
            $this->attendanceService
            ->calculateBalance(
                $date,
                $workedMinutes
            );

        return
            $this->attendanceService
            ->formatBalance(
                $balance
            );
    }
}
