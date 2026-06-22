<?php
session_start();

if (!isset($_SESSION['employee_id'])) {
    die('Please Login');
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Employee Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f4f7fb;
            min-height: 100vh;
        }

        .header {
            background: #2563eb;
            color: white;
            padding: 25px 20px;
            border-radius: 0 0 25px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h2 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        .header-left p {
            opacity: .9;
        }

        .logout-btn {
            background: white;
            color: #2563eb;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            transition: .3s;
        }

        .logout-btn:hover {
            background: #e2e8f0;
        }

        .container {
            padding: 20px;
        }

        .profile-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
            margin-top: -20px;
        }

        .profile-row {
            margin-bottom: 12px;
        }

        .profile-row label {
            display: block;
            color: #666;
            font-size: 13px;
        }

        .profile-row span {
            font-weight: 600;
            color: #222;
        }

        .actions {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .actions button,
        .actions a {
            text-decoration: none;
            border: none;
            text-align: center;
            padding: 16px;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .checkin-btn {
            background: #16a34a;
        }

        #taskForm button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        #taskForm button:hover {
            background: #1d4ed8;
        }

        .checkout-btn {
            background: #dc2626;
        }

        .attendance-btn {
            background: #2563eb;
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
        }

        .modal-content {
            width: 90%;
            max-width: 500px;
            background: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
        }

        textarea {
            width: 100%;
            height: 80px;
            margin-bottom: 10px;
        }

        #message {
            margin-top: 15px;
            display: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            color: #888;
            font-size: 12px;
        }

        @media(max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .logout-btn {
                width: 100%;
                max-width: 200px;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="header-left">
            <h2>Employee Dashboard</h2>
            <p>Attendance Management System</p>
        </div>
        <a href="/pcl_attendence_sheet/public/auth/logout.php" class="logout-btn">
            🚪 Logout
        </a>
    </div>

    <div class="container">

        <div class="profile-card">
            <div class="profile-row">
                <label>Employee Name</label>
                <span><?= htmlspecialchars($_SESSION['employee_name']) ?></span>
            </div>
            <div class="profile-row">
                <label>Employee ID</label>
                <span><?= htmlspecialchars($_SESSION['employee_id']) ?></span>
            </div>
        </div>

        <div class="actions">
            <button id="checkInBtn" class="checkin-btn">📍 Check In</button>
            <div id="taskModal" class="modal">

                <div class="modal-content">

                    <h2>Today's Tasks</h2>

                    <form id="taskForm">

                        <textarea
                            name="task1"
                            placeholder="Enter your first planned task *"
                            required></textarea>

                        <textarea
                            name="task2"
                            placeholder="Enter another task (optional)"></textarea>

                        <textarea
                            name="task3"
                            placeholder="Enter another task (optional)"></textarea>

                        <button type="submit">
                            Save Tasks
                        </button>

                    </form>

                </div>

            </div>
            <button id="checkOutBtn" class="checkout-btn">🚪 Check Out</button>
            <a href="../attendence/my_attendance.php" class="attendance-btn">📅 My Attendance</a>
        </div>

        <div id="message"></div>

        <div class="footer">PCL Attendance System</div>

    </div>

    <script>
        // ─── Prevent resubmission on refresh ────────────────────────────────
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // ─── Google API Key ──────────────────────────────────────────────────
        const GOOGLE_API_KEY = 'AIzaSyDzayzpvXPIN14oWJR8k_io0ncKiRZ3SIQ';

        // ─── Helper: show message ────────────────────────────────────────────
        function showMessage(text, type = 'info') {
            const message = document.getElementById('message');
            message.style.display = 'block';

            const styles = {
                info: {
                    bg: '#dbeafe',
                    color: '#1e40af'
                },
                success: {
                    bg: '#dcfce7',
                    color: '#166534'
                },
                error: {
                    bg: '#fee2e2',
                    color: '#991b1b'
                },
            };

            const s = styles[type] || styles.info;
            message.style.background = s.bg;
            message.style.color = s.color;
            message.innerHTML = text;
        }

        // ─── Reverse Geocoding: lat/lng → city, state, pincode, address ──────
        async function getAddressFromLatLng(lat, lng) {
            const url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${GOOGLE_API_KEY}`;

            const response = await fetch(url);
            const data = await response.json();

            if (data.status !== 'OK' || !data.results.length) {
                throw new Error('Address not found (status: ' + data.status + ')');
            }

            const components = data.results[0].address_components;
            const fullAddress = data.results[0].formatted_address;

            let city = '';
            let state = '';
            let pincode = '';
            let country = '';

            components.forEach(comp => {
                if (comp.types.includes('locality'))
                    city = comp.long_name;
                if (comp.types.includes('administrative_area_level_1'))
                    state = comp.long_name;
                if (comp.types.includes('postal_code'))
                    pincode = comp.long_name;
                if (comp.types.includes('country'))
                    country = comp.long_name;
            });

            return {
                city,
                state,
                pincode,
                country,
                fullAddress
            };
        }

        // ─── Common function: location → geocode → POST to given URL ─────────
        function sendAttendanceWithLocation(url, loadingMsg) {

            showMessage(loadingMsg, 'info');

            navigator.geolocation.getCurrentPosition(

                async function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        showMessage('🗺️ Fetching address details...', 'info');

                        try {
                            const {
                                city,
                                state,
                                pincode,
                                country,
                                fullAddress
                            } =
                            await getAddressFromLatLng(lat, lng);

                            showMessage('⏳ Please wait...', 'info');

                            const formData = new FormData();
                            formData.append('latitude', lat);
                            formData.append('longitude', lng);
                            formData.append('city', city);
                            formData.append('state', state);
                            formData.append('pincode', pincode);
                            formData.append('country', country);
                            formData.append('address', fullAddress);

                            const response = await fetch(url, {
                                method: 'POST',
                                body: formData
                            });

                            const data = await response.json();

                            if (data.success) {
                                showMessage(
                                    "✅ Check In Successful<br><br>" +
                                    "🏢 Status : " + data.status + "<br>" +
                                    "🏙️ City : " + data.city + "<br>" +
                                    "📮 Pincode : " + data.pincode + "<br>" +
                                    "🗺️ State : " + data.state + "<br>" +
                                    "🌍 Country : " + data.country + "<br>" +
                                    "📌 Address : " + data.address + "<br>" +
                                    "📏 Distance : " + data.distance + " meters",
                                    'success'
                                );

                                document.getElementById('taskModal').style.display = 'block';

                                document.getElementById('taskForm').dataset.attendance =
                                    data.attendance_id;

                            } else {

                                showMessage(
                                    data.message || 'error'
                                );
                            }

                        } catch (err) {
                            showMessage('❌ Address fetch failed: ' + err.message, 'error');
                        }
                    },

                    function(error) {

                        console.log(error);

                        let msg = '';

                        switch (error.code) {

                            case 1:
                                msg = 'Permission Denied';
                                break;

                            case 2:
                                msg = 'Position Unavailable';
                                break;

                            case 3:
                                msg = 'Timeout';
                                break;

                            default:
                                msg = error.message;
                        }

                        showMessage(
                            '❌ Error Code: ' + error.code +
                            '<br>Error: ' + msg +
                            '<br>Message: ' + error.message,
                            'error'
                        );
                    }, {
                        enableHighAccuracy: true
                    }
            );
        }

        // ─── CHECK IN ────────────────────────────────────────────────────────
        document.getElementById('checkInBtn').addEventListener('click', function() {
            sendAttendanceWithLocation(
                '/pcl_attendence_sheet/public/employee/checkin.php',
                '📍 Detecting your location...'
            );
        });

        // ─── CHECK OUT ───────────────────────────────────────────────────────
        document.getElementById('checkOutBtn').addEventListener('click', function() {
            sendAttendanceWithLocation(
                '/pcl_attendence_sheet/public/employee/checkout.php',
                '📍 Detecting your location for checkout...'
            );
        });


        document.getElementById("taskForm").addEventListener("submit", async function(e) {

            e.preventDefault();

            let fd = new FormData(this);

            fd.append(
                "attendance_id",
                this.dataset.attendance
            );

            const response = await fetch(
                "/pcl_attendence_sheet/public/employee/save_tasks.php", {
                    method: "POST",
                    body: fd
                }
            );

            const result = await response.json();

            if (result.success) {

                alert("Tasks Saved Successfully");

                document.getElementById("taskModal").style.display = "none";

            } else {

                alert(result.message);

            }

        });
    </script>

</body>

</html>