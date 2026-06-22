<?php

$routes = [

    '/' => 'AuthController@login',

    '/employee/checkin' =>
    'AttendanceController@checkIn',

    '/employee/checkout' =>
    'AttendanceController@checkOut',

    '/admin/employees' =>
    'EmployeeController@index'
];
