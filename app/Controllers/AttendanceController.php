<?php

require_once __DIR__ . '/../Services/LocationService.php';

class AttendanceController
{
    public function checkIn()
    {
        global $conn;

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            die('Invalid Request');
        }

        if (!isset($_SESSION['employee_id'])) {
            die('Please login first');
        }

        $employeeId = (int)$_SESSION['employee_id'];

        $latitude  = isset($_POST['latitude'])  ? (float)$_POST['latitude']  : 0;
        $longitude = isset($_POST['longitude']) ? (float)$_POST['longitude'] : 0;
        $city      = mysqli_real_escape_string($conn, $_POST['city']    ?? '');
        $state     = mysqli_real_escape_string($conn, $_POST['state']   ?? '');
        $pincode   = mysqli_real_escape_string($conn, $_POST['pincode'] ?? '');
        $country   = mysqli_real_escape_string($conn, $_POST['country'] ?? '');
        $address   = mysqli_real_escape_string($conn, $_POST['address'] ?? '');

        if ($latitude == 0 || $longitude == 0) {
            die('❌ Location not detected.');
        }

        // Already checked in today?
        $todayAttendance = mysqli_query(
            $conn,
            "SELECT id FROM attendance
             WHERE employee_id = $employeeId
             AND attendance_date = CURDATE()"
        );

        if (mysqli_num_rows($todayAttendance) > 0) {
            die('⚠️ You have already checked in today.');
        }

        // Get office location
        $office = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT * FROM office_location LIMIT 1")
        );

        if (!$office) {
            die('❌ Office location not configured.');
        }

        $locationService = new LocationService();
        $distance = $locationService->getDistance(
            $latitude,
            $longitude,
            $office['latitude'],
            $office['longitude']
        );

        $status = $distance <= $office['radius'] ? 'Office' : 'Outside';

        $sql = "
            INSERT INTO attendance (
                employee_id,
                attendance_date,
                check_in,
                checkin_city,
                checkin_pincode,
                checkin_address,
                checkin_state,
                checkin_latitude,
                checkin_longitude,
                checkin_country,
                location_status
            ) VALUES (
                '$employeeId',
                CURDATE(),
                NOW(),
                '$city',
                '$pincode',
                '$address',
                '$state',
                '$latitude',
                '$longitude',
                '$country',
                '$status'
            )
        ";

        if (mysqli_query($conn, $sql)) {

            echo json_encode([
                'success' => true,
                'attendance_id' => mysqli_insert_id($conn),

                'status' => $status,
                'city' => $city,
                'pincode' => $pincode,
                'state' => $state,
                'country' => $country,
                'address' => $address,
                'distance' => round($distance, 2)
            ]);

            exit;
        } else {

            echo json_encode([
                'success' => false,
                'message' => mysqli_error($conn)
            ]);

            exit;
        }
    }
    public function checkOut()
    {
        global $conn;

        // echo "<pre>";
        // print_r($_POST);
        // die;

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            die('Invalid Request');
        }

        if (!isset($_SESSION['employee_id'])) {
            die('Please login first');
        }

        $employeeId = (int)$_SESSION['employee_id'];

        $latitude  = isset($_POST['latitude'])  ? (float)$_POST['latitude']  : 0;
        $longitude = isset($_POST['longitude']) ? (float)$_POST['longitude'] : 0;
        $city      = mysqli_real_escape_string($conn, $_POST['city']    ?? '');
        $state     = mysqli_real_escape_string($conn, $_POST['state']   ?? '');
        $pincode   = mysqli_real_escape_string($conn, $_POST['pincode'] ?? '');
        $country   = mysqli_real_escape_string($conn, $_POST['country'] ?? '');
        $address   = mysqli_real_escape_string($conn, $_POST['address'] ?? '');

        // Get today's attendance record
        $todayResult = mysqli_query(
            $conn,
            "SELECT id FROM attendance
             WHERE employee_id = $employeeId
             AND attendance_date = CURDATE()
             LIMIT 1"
        );

        if (mysqli_num_rows($todayResult) == 0) {
            die('⚠️ You have not checked in today.');
        }

        $todayRow = mysqli_fetch_assoc($todayResult);
        $attendanceId = (int)$todayRow['id'];

        // Already checked out?
        $checkOutResult = mysqli_query(
            $conn,
            "SELECT id FROM attendance
             WHERE id = $attendanceId
             AND check_out IS NOT NULL"
        );

        if (mysqli_num_rows($checkOutResult) > 0) {
            die('⚠️ You have already checked out today.');
        }

        // Get office location
        $office = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT * FROM office_location LIMIT 1")
        );

        if (!$office) {
            die('❌ Office location not configured.');
        }

        // Calculate distance only if lat/lng provided
        $status = 'Unknown';
        if ($latitude != 0 && $longitude != 0) {
            $locationService = new LocationService();
            $distance = $locationService->getDistance(
                $latitude,
                $longitude,
                $office['latitude'],
                $office['longitude']
            );
            $status = $distance <= $office['radius'] ? 'Office' : 'Outside';
        }

        $sql = "
            UPDATE attendance SET
                check_out          = NOW(),
                checkout_city      = '$city',
                checkout_pincode   = '$pincode',
                checkout_address   = '$address',
                checkout_state     = '$state',
                checkout_latitude  = '$latitude',
                checkout_longitude = '$longitude',
                checkout_country   = '$country',
                location_status    = '$status'
            WHERE id = $attendanceId
        ";

        if (mysqli_query($conn, $sql)) {
            echo "✅ Check Out Successful<br><br>";
            echo "🏢 Status   : " . $status . "<br><br>";
            echo "🏙️ City     : " . $city . "<br>";
            echo "📮 Pincode  : " . $pincode . "<br>";
            echo "🗺️ State    : " . $state . "<br>";
            echo "🌍 Country  : " . $country . "<br>";
            echo "📌 Address  : " . $address;
        } else {
            echo "❌ " . mysqli_error($conn);
        }
    }
}
