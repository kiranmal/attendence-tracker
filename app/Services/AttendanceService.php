<?php

/**
 * AttendanceService
 * Core business logic for working-hours and balance calculations.
 *
 * Rules:
 *  Monday – Friday : required 9 hrs = 540 min.  Balance = worked - 540
 *  Saturday        : OFF day (0 required).       Balance = worked (all bonus)
 *  Sunday          : OFF day (0 required).       Balance = worked (all bonus)
 *  Extra hours any day are added to balance.
 */
class AttendanceService
{
    const REQUIRED_MINUTES_WEEKDAY = 540; // 9 hours

    /** Minutes between two datetime strings. Returns 0 if either is empty. */
    public function calculateWorkedMinutes($checkIn, $checkOut): float
    {
        if (!$checkIn || !$checkOut) return 0;
        $diff = strtotime($checkOut) - strtotime($checkIn);
        return max(0, $diff / 60);
    }

    /** Is a date (Y-m-d) a Saturday? */
    public function isSaturday($date): bool
    {
        return date('N', strtotime($date)) == 6;
    }

    /** Is a date (Y-m-d) a Sunday? */
    public function isSunday($date): bool
    {
        return date('N', strtotime($date)) == 7;
    }

    /** Is a date a weekend (Sat or Sun)? */
    public function isWeekend($date): bool
    {
        return $this->isSaturday($date) || $this->isSunday($date);
    }

    /**
     * Balance minutes for a single day.
     *   Weekday : workedMins - 540  (negative = short, positive = extra)
     *   Weekend : workedMins        (all bonus)
     */
    public function calculateBalance($date, float $workedMinutes): float
    {
        // Sunday
        if ($this->isSunday($date)) {
            return $workedMinutes;
        }

        // Saturday
        if ($this->isSaturday($date)) {
            return 0;
        }

        // Monday-Friday
        return $workedMinutes - self::REQUIRED_MINUTES_WEEKDAY;
    }

    /** Format minutes as ±Hh MMm  e.g. "+1h 30m" or "-0h 45m" */
    public function formatBalance(float $minutes): string
    {
        $sign = $minutes >= 0 ? '+' : '-';
        $abs  = abs((int)$minutes);
        $h    = floor($abs / 60);
        $m    = $abs % 60;
        return $sign . $h . 'h ' . str_pad($m, 2, '0', STR_PAD_LEFT) . 'm';
    }

    /** Format raw minutes as Hh MMm  e.g. "7h 30m" */
    public function formatDuration(float $minutes): string
    {
        $abs = abs((int)$minutes);
        $h   = floor($abs / 60);
        $m   = $abs % 60;
        return $h . 'h ' . str_pad($m, 2, '0', STR_PAD_LEFT) . 'm';
    }

    /** Day-type label: Weekday / Saturday / Sunday */
    public function dayType($date): string
    {
        if ($this->isSunday($date))   return 'Sunday';
        if ($this->isSaturday($date)) return 'Saturday';
        return 'Weekday';
    }
}
