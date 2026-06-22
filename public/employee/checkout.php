<?php

session_start();

require_once '../../config/database.php';
require_once '../../app/Controllers/AttendanceController.php';

$attendanceController =
    new AttendanceController();

$attendanceController->checkOut();
